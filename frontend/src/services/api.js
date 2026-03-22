const API_URL = 'http://localhost/sistema-de-ticketes/index.php';

export const apiFetch = async (action, options = {}) => {
  const url = `${API_URL}?action=${action}`;

  const headers = {
    'Accept': 'application/json',
    ...(options.headers || {})
  };

  // If there's a body and it's an object, stringify it
  if (options.body && typeof options.body === 'object') {
    options.body = JSON.stringify(options.body);
    headers['Content-Type'] = 'application/json';
  }

  // Ensure cookies are sent for standard PHP sessions to work
  const fetchOptions = {
    ...options,
    headers,
    credentials: 'init' // Required for PHP $_SESSION cookies
  };

  // Set include so cross-origin requests send cookies on localhost
  fetchOptions.credentials = 'include';

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
