const express = require("express");
const fs = require("fs");
const path = require("path");
const cors = require("cors");
const dotenv = require("dotenv");
const jwt = require("jsonwebtoken");
const crypto = require("crypto");
const multer = require("multer");
const { v4: uuidv4 } = require("uuid");

dotenv.config({ path: path.join(__dirname, "..", ".env") });

const app = express();

// Middleware
app.use(cors());
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Logging middleware
app.use((req, res, next) => {
  console.log(`${new Date().toISOString()} - ${req.method} ${req.path}`);
  next();
});

const PORT = process.env.PORT || 3000;
const DB_PATH = path.join(__dirname, "..", "db-products.json");
const UPLOADS_DIR = path.join(__dirname, "..", "uploads");

// Ensure directories exist
if (!fs.existsSync(UPLOADS_DIR)) {
  fs.mkdirSync(UPLOADS_DIR, { recursive: true });
}

// Initialize DB if doesn't exist
if (!fs.existsSync(DB_PATH)) {
  fs.writeFileSync(DB_PATH, JSON.stringify([], null, 2), "utf8");
}

// Serve static files from root directory
app.use(
  express.static(path.join(__dirname, ".."), {
    index: false, // Don't auto-serve index.html for all routes
    dotfiles: "ignore",
  })
);
app.use("/uploads", express.static(UPLOADS_DIR));

// Database helpers
function readDB() {
  try {
    const raw = fs.readFileSync(DB_PATH, "utf8");
    return JSON.parse(raw);
  } catch (e) {
    console.error("Error reading database:", e);
    return [];
  }
}

function writeDB(data) {
  try {
    fs.writeFileSync(DB_PATH, JSON.stringify(data, null, 2), "utf8");
  } catch (e) {
    console.error("Error writing database:", e);
    throw new Error("Database write failed");
  }
}

// Multer configuration for file uploads
const storage = multer.diskStorage({
  destination: function (req, file, cb) {
    cb(null, UPLOADS_DIR);
  },
  filename: function (req, file, cb) {
    const ext = path.extname(file.originalname);
    const filename = `${Date.now()}-${Math.random()
      .toString(36)
      .slice(2, 8)}${ext}`;
    cb(null, filename);
  },
});

const fileFilter = (req, file, cb) => {
  // Accept images only
  if (file.mimetype.startsWith("image/")) {
    cb(null, true);
  } else {
    cb(new Error("Solo se permiten archivos de imagen"), false);
  }
};

const upload = multer({
  storage,
  fileFilter,
  limits: {
    fileSize: 5 * 1024 * 1024, // 5MB max
  },
});

// Auth helpers
const ADMIN_USER = process.env.ADMIN_USER || "admin";
const ADMIN_PASS_HASH = process.env.ADMIN_PASS_HASH || "";
const JWT_SECRET = process.env.JWT_SECRET || "dev_jwt_secret";

function sha256hex(text) {
  return crypto.createHash("sha256").update(text, "utf8").digest("hex");
}

function checkAuth(req, res, next) {
  const auth = req.headers.authorization;
  if (!auth) return res.status(401).json({ error: "Missing authorization" });

  const parts = auth.split(" ");
  if (parts.length !== 2 || parts[0] !== "Bearer") {
    return res.status(401).json({ error: "Invalid authorization" });
  }

  try {
    const payload = jwt.verify(parts[1], JWT_SECRET);
    req.user = payload;
    next();
  } catch (e) {
    return res.status(401).json({ error: "Invalid token" });
  }
}

// ============================================
// PUBLIC API ROUTES
// ============================================

// Get all products with filters
app.get("/api/products", (req, res) => {
  try {
    const { category, q, size, brand, page = 1, limit = 100 } = req.query;
    let items = readDB();

    // Apply filters
    if (category) {
      items = items.filter(
        (p) => p.category && p.category.toLowerCase() === category.toLowerCase()
      );
    }

    if (brand) {
      items = items.filter(
        (p) => p.brand && p.brand.toLowerCase().includes(brand.toLowerCase())
      );
    }

    if (size) {
      items = items.filter((p) => (p.sizes || []).includes(size));
    }

    if (q) {
      const query = q.toLowerCase();
      items = items.filter((p) =>
        (p.name + " " + (p.brand || "") + " " + (p.description || ""))
          .toLowerCase()
          .includes(query)
      );
    }

    // Pagination
    const pageNum = Number(page);
    const limitNum = Number(limit);
    const start = (pageNum - 1) * limitNum;
    const paged = items.slice(start, start + limitNum);

    res.json({
      total: items.length,
      page: pageNum,
      limit: limitNum,
      items: paged,
    });
  } catch (error) {
    console.error("Error fetching products:", error);
    res.status(500).json({ error: "Error fetching products" });
  }
});

// Get single product by ID
app.get("/api/products/:id", (req, res) => {
  try {
    const id = req.params.id;
    const items = readDB();
    const product = items.find((x) => x.id === id);

    if (!product) {
      return res.status(404).json({ error: "Product not found" });
    }

    res.json(product);
  } catch (error) {
    console.error("Error fetching product:", error);
    res.status(500).json({ error: "Error fetching product" });
  }
});

// ============================================
// ADMIN AUTH ROUTES
// ============================================

// Admin login
app.post("/api/admin/login", (req, res) => {
  try {
    const { username, password } = req.body;

    if (!username || !password) {
      return res.status(400).json({ error: "Username and password required" });
    }

    if (username.toLowerCase() !== ADMIN_USER.toLowerCase()) {
      return res.status(401).json({ error: "Invalid credentials" });
    }

    if (!ADMIN_PASS_HASH) {
      return res.status(500).json({
        error: "Admin password not configured. Check .env file",
      });
    }

    if (sha256hex(password) !== ADMIN_PASS_HASH) {
      return res.status(401).json({ error: "Invalid credentials" });
    }

    const token = jwt.sign({ user: ADMIN_USER }, JWT_SECRET, {
      expiresIn: "8h",
    });

    res.json({ token });
  } catch (error) {
    console.error("Login error:", error);
    res.status(500).json({ error: "Login failed" });
  }
});

// Verify admin token
app.get("/api/admin/me", checkAuth, (req, res) => {
  try {
    res.json({ user: req.user });
  } catch (error) {
    console.error("Auth check error:", error);
    res.status(500).json({ error: "Auth check failed" });
  }
});

// Admin logout (client-side handles token removal)
app.post("/api/admin/logout", checkAuth, (req, res) => {
  try {
    res.json({ ok: true });
  } catch (error) {
    console.error("Logout error:", error);
    res.status(500).json({ error: "Logout failed" });
  }
});

// ============================================
// ADMIN PRODUCT ROUTES
// ============================================

// Get all products (admin)
app.get("/api/admin/products", checkAuth, (req, res) => {
  try {
    const items = readDB();
    res.json(items);
  } catch (error) {
    console.error("Error fetching admin products:", error);
    res.status(500).json({ error: "Error fetching products" });
  }
});

// Create product
app.post(
  "/api/admin/product",
  checkAuth,
  upload.array("images", 6),
  (req, res) => {
    try {
      const body = req.body;
      const files = req.files || [];
      const items = readDB();

      const product = {
        id: uuidv4(),
        name: body.name || "Sin nombre",
        category: body.category || "Uncategorized",
        brand: body.brand || "",
        price: Number(body.price || 0),
        stock: Number(body.stock || 0),
        sizes: body.sizes
          ? Array.isArray(body.sizes)
            ? body.sizes
            : body.sizes
                .split(",")
                .map((s) => s.trim())
                .filter(Boolean)
          : [],
        images: files.map((f) => "/uploads/" + path.basename(f.path)),
        description: body.description || "",
        createdAt: new Date().toISOString(),
      };

      items.unshift(product);
      writeDB(items);

      console.log(`Product created: ${product.name} (${product.id})`);
      res.json({ ok: true, product });
    } catch (error) {
      console.error("Error creating product:", error);
      res.status(500).json({ error: "Error creating product" });
    }
  }
);

// Update product
app.put(
  "/api/admin/product/:id",
  checkAuth,
  upload.array("images", 6),
  (req, res) => {
    try {
      const id = req.params.id;
      const items = readDB();
      const idx = items.findIndex((x) => x.id === id);

      if (idx === -1) {
        return res.status(404).json({ error: "Product not found" });
      }

      const body = req.body;
      const files = req.files || [];
      const current = items[idx];

      current.name = body.name || current.name;
      current.category = body.category || current.category;
      current.brand = body.brand || current.brand;
      current.price = Number(body.price || current.price);
      current.stock = Number(body.stock || current.stock);
      current.sizes = body.sizes
        ? Array.isArray(body.sizes)
          ? body.sizes
          : body.sizes
              .split(",")
              .map((s) => s.trim())
              .filter(Boolean)
        : current.sizes;

      if (files.length) {
        current.images = files.map((f) => "/uploads/" + path.basename(f.path));
      }

      current.description = body.description || current.description;
      current.updatedAt = new Date().toISOString();

      items[idx] = current;
      writeDB(items);

      console.log(`Product updated: ${current.name} (${current.id})`);
      res.json({ ok: true, product: current });
    } catch (error) {
      console.error("Error updating product:", error);
      res.status(500).json({ error: "Error updating product" });
    }
  }
);

// Delete product
app.delete("/api/admin/product/:id", checkAuth, (req, res) => {
  try {
    const id = req.params.id;
    let items = readDB();
    const idx = items.findIndex((x) => x.id === id);

    if (idx === -1) {
      return res.status(404).json({ error: "Product not found" });
    }

    // Delete product images from disk
    const prod = items[idx];
    if (prod.images && prod.images.length) {
      prod.images.forEach((img) => {
        try {
          const imgPath = path.join(__dirname, "..", img);
          if (fs.existsSync(imgPath)) {
            fs.unlinkSync(imgPath);
            console.log(`Deleted image: ${img}`);
          }
        } catch (e) {
          console.error(`Error deleting image ${img}:`, e);
        }
      });
    }

    items = items.filter((x) => x.id !== id);
    writeDB(items);

    console.log(`Product deleted: ${prod.name} (${prod.id})`);
    res.json({ ok: true });
  } catch (error) {
    console.error("Error deleting product:", error);
    res.status(500).json({ error: "Error deleting product" });
  }
});

// ============================================
// FALLBACK ROUTES
// ============================================

// Admin routes
app.get("/admin/dashboard.html", (req, res) => {
  res.sendFile(path.join(__dirname, "..", "admin", "dashboard.html"));
});

app.get("/admin*", (req, res) => {
  res.sendFile(path.join(__dirname, "..", "admin", "login.html"));
});

// Main app - serve index.html for all other routes
app.get("*", (req, res) => {
  res.sendFile(path.join(__dirname, "..", "index.html"));
});

// ============================================
// ERROR HANDLING
// ============================================

app.use((err, req, res, next) => {
  console.error("Server error:", err);
  res.status(500).json({ error: err.message || "Internal server error" });
});

// ============================================
// START SERVER
// ============================================

app.listen(PORT, () => {
  console.log(`
╔════════════════════════════════════════╗
║       SHOP33 SERVER RUNNING           ║
╠════════════════════════════════════════╣
║  URL: http://localhost:${PORT}           ║
║  Environment: ${process.env.NODE_ENV || "development"}              ║
║  Admin: ${ADMIN_USER}                        ║
╚════════════════════════════════════════╝
  `);

  if (!ADMIN_PASS_HASH) {
    console.warn(`
⚠️  WARNING: Admin password not configured!
    Set ADMIN_PASS_HASH in .env file
    Run: node -e "console.log(require('crypto').createHash('sha256').update('YOUR_PASSWORD').digest('hex'))"
    `);
  }
});

// Graceful shutdown
process.on("SIGTERM", () => {
  console.log("SIGTERM received, closing server...");
  process.exit(0);
});

process.on("SIGINT", () => {
  console.log("\nSIGINT received, closing server...");
  process.exit(0);
});
