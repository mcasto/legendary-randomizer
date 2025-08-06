import { useStore } from "src/stores/store";
import { Notify } from "quasar";
import wretch from "wretch";

export default ({
  path,
  method,
  payload,
  useAuth = false,
  showError = true,
}) => {
  const store = useStore();

  // Initialize the base request
  let request = useAuth
    ? wretch("/api").auth(`Bearer ${store.token}`)
    : wretch("/api");

  // Handle GET vs. other methods
  if (method === "get" && payload) {
    // Append payload to path for GET (e.g., /api/test/1)

    const payloadValue = Object.values(payload).shift();

    const url = `${path}/${payloadValue}`;

    request = request.url(url);
  } else {
    // For non-GET methods, use payload as the body
    request = request.url(path);
    if (payload) {
      request = request.json(payload); // Set JSON body
    }
  }

  // Execute the request
  return request[method]()
    .json()
    .then((response) => {
      if (response.status == "error" && showError) {
        Notify.create({
          type: "negative",
          message: response.message,
          html: true,
        });
        return false;
      }
      return response;
    })
    .catch((e) => {
      return { error: e };
    });
};
