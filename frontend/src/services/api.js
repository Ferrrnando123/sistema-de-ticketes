const normalizeApiUrl = () => {
  const envUrl = (import.meta.env.VITE_API_URL || '').trim();
  const fallback = '/api.php';

  if (!envUrl) return fallback;

  // Si por error se dejo una URL local en Railway/produccion, usamos same-origin.
  if (typeof window !== 'undefined') {
    const isProdHost = !/^(localhost|127\.0\.0\.1)$/i.test(window.location.hostname);
    const envLooksLocal = /localhost|127\.0\.0\.1/i.test(envUrl);
    if (isProdHost && envLooksLocal) return fallback;
  }

  return envUrl;
};

const API_URL = normalizeApiUrl();

export const apiFetch = async (action, options = {}) => {
  const separator = API_URL.includes('?') ? '&' : '?';
  const url = `${API_URL}${separator}action=${encodeURIComponent(action)}`;

  const headers = {
    'Accept': 'application/json',
    ...(options.headers || {})
  };

  // Si hay body y es un FormData, no lo tocamos ni le forzamos Content-Type
  if (options.body instanceof FormData) {
    // El navegador asignará Content-Type: multipart/form-data automáticamente
  } else if (options.body && typeof options.body === 'object') {
    options.body = JSON.stringify(options.body);
    headers['Content-Type'] = 'application/json';
  }

  // Ensure cookies are sent for standard PHP sessions to work
  const fetchOptions = {
    ...options,
    headers,
    credentials: 'include'
  };

  try {
    const response = await fetch(url, fetchOptions);
    const data = await response.json();

    // We can handle specific HTTP status codes here if we want
    // Nuestro backend siempre retorna un JSON estructurado aunque el HTTP status sea 4xx/5xx
    // Por ende, podemos retornar el 'data' para leer success/message nativamente sin romper la promesa.
    if (!response.ok && !data) {
        throw new Error('Error en la petición');
    }
    
    return data;
  } catch (error) {
      console.error(`API Error on action [${action}]: `, error);
      throw error;
    }
};
