
const API_URL = 'http://localhost:8000/index.php';

export const apiFetch = async (action, options = {}) => {
  // 2.  la URL correcta
  const url = `${API_URL}?action=${action}`;

  const headers = {
    'Accept': 'application/json',
    ...(options.headers || {})
  };

  // Manejo de el cuerpo de la petición (JSON o FormData)
  if (options.body instanceof FormData) {
    // El navegador asignará Content-Type: multipart/form-data automáticamente
  } else if (options.body && typeof options.body === 'object') {
    options.body = JSON.stringify(options.body);
    headers['Content-Type'] = 'application/json';
  }

  // Configuración de fetch
  const fetchOptions = {
    ...options,
    headers,
    // 'include' es vital para que las cookies de sesión de PHP (puerto 8000) 
    // se guarden en el navegador del frontend (puerto 5173)
    credentials: 'include' 
  };

  try {
    const response = await fetch(url, fetchOptions);
    
    // Intentamos parsear el JSON de respuesta
    const data = await response.json();

    // Si la respuesta no es 2xx pero tenemos un mensaje del backend, lo usamos
    if (!response.ok) {
        console.error(`Error del servidor (${response.status}):`, data.message);
    }
    
    return data;
  } catch (error) {
      console.error(`Error de red o conexión en la acción [${action}]: `, error);
      throw error;
    }
};