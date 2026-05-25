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

const getStoredAccessToken = () => {
  if (typeof window === 'undefined') return '';
  try {
    return window.localStorage.getItem('udb_ticket_token') || '';
  } catch {
    return '';
  }
};

export const apiFetch = async (action, options = {}) => {
  const separator = API_URL.includes('?') ? '&' : '?';

  // Permite llamadas legacy como "endpoint&param=1" sin romper la API rutera.
  const actionString = String(action || '');
  const ampIndex = actionString.indexOf('&');
  const endpoint = ampIndex >= 0 ? actionString.slice(0, ampIndex) : actionString;
  const extraQuery = ampIndex >= 0 ? actionString.slice(ampIndex + 1) : '';
  const url = `${API_URL}${separator}action=${encodeURIComponent(endpoint)}${extraQuery ? `&${extraQuery}` : ''}`;

  const headers = {
    'Accept': 'application/json',
    ...(options.headers || {})
  };

  const storedToken = getStoredAccessToken();
  if (storedToken && !headers.Authorization) {
    headers.Authorization = `Bearer ${storedToken}`;
  }

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
