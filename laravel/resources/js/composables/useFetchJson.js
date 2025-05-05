import { ref } from 'vue';
import { fetchJson } from '../utils/fetchJson';

/**
 * Composable to fetch JSON data and expose refs along with abort functionality
 *
 * @param {Object|string} options - Either a configuration object or a URL string
 * @param {string} [options.url] - The URL to fetch (mandatory if using an object)
 * @param {object} [options.data=null] - The data to send (if any)
 * @param {string} [options.method=null] - The method to use (GET, POST, PUT, DELETE, etc.)
 *   If not specified, it will be GET if data is null, POST otherwise
 * @param {object} [options.headers={}] - The additional headers to send (if any)
 * @param {number} [options.timeout=5000] - Timeout in milliseconds
 * @param {string} [options.baseUrl=null] - The base URL to use for the request (optional)
 * @returns {Object} An object with reactive refs and the abort function
 * @property {Ref} data - The fetched data
 * @property {Ref} error - The error object (if any)
 * @property {Ref} loading - Indicates loading state
 * @property {Function} abort - Function to abort the request
 */
export function useFetchJson(options) {
  const data = ref(null);
  const error = ref(null);
  const loading = ref(true);

  const { request, abort } = fetchJson(options);
  request
    .then(res => {
      data.value = res;
      loading.value = false;
    })
    .catch(err => {
      error.value = err;
      loading.value = false;
    });

  return { data, error, loading, abort };
}