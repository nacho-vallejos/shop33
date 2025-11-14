# Video de Fondo

## Instrucciones

Coloca tu archivo de video **skater.mp4** en esta carpeta.

### Especificaciones recomendadas:
- **Formato**: MP4 (H.264 codec)
- **Resolución**: 1920x1080 (Full HD) o superior
- **Duración**: 10-30 segundos (se reproducirá en loop)
- **Tamaño**: Optimizado para web (< 10MB recomendado)
- **FPS**: 30 fps
- **Bitrate**: 2-5 Mbps

### Ejemplo para optimizar video (con ffmpeg):

```bash
ffmpeg -i input.mp4 -vf "scale=1920:1080" -c:v libx264 -preset slow -crf 23 -an skater.mp4
```

### Sugerencias de contenido:
- Skaters haciendo trucos
- Escenas urbanas con patinadores
- Sesiones de skate en skatepark
- POV de skateboarding
- Ambiente callejero/urbano

### Sitios para descargar videos gratis:
- **Pexels Videos**: https://www.pexels.com/search/videos/skateboard/
- **Pixabay**: https://pixabay.com/videos/search/skate/
- **Coverr**: https://coverr.co/
- **Videvo**: https://www.videvo.net/

Una vez colocado el archivo `skater.mp4` aquí, el video se reproducirá automáticamente como fondo en la página principal.
