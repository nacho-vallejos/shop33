// SHOP33 - Admin Login Script

const loginForm = document.getElementById("loginForm");
const btnLogin = document.getElementById("btnLogin");
const errorMsg = document.getElementById("errorMsg");

loginForm.addEventListener("submit", async (e) => {
  e.preventDefault();

  const username = document.getElementById("username").value.trim();
  const password = document.getElementById("password").value;

  if (!username || !password) {
    showError("Por favor completa todos los campos");
    return;
  }

  btnLogin.disabled = true;
  btnLogin.textContent = "Iniciando...";
  hideError();

  try {
    const apiUrl = new URL("./api/admin/login", window.location.href);
    const res = await fetch(apiUrl, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ username, password }),
      credentials: "same-origin",
    });

    const data = await res.json();

    if (res.ok && data.token) {
      // Login exitoso - guardar token
      localStorage.setItem("admin_token", data.token);
      window.location.href = "/admin";
    } else {
      // Error de login
      const errorText =
        data.error === "Invalid credentials"
          ? "Usuario o contraseña incorrectos"
          : "Error al iniciar sesión";
      showError(errorText);
    }
  } catch (err) {
    console.error("Login error:", err);
    showError("Error de conexión. Intenta de nuevo.");
  } finally {
    btnLogin.disabled = false;
    btnLogin.textContent = "Iniciar Sesión";
  }
});

function showError(msg) {
  errorMsg.textContent = msg;
  errorMsg.classList.add("show");
}

function hideError() {
  errorMsg.classList.remove("show");
  errorMsg.textContent = "";
}

// Auto-focus al campo de usuario
document.getElementById("username").focus();
