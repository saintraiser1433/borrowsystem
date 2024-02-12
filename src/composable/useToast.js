import { toast } from "vue3-toastify";

export function Toaster() {
  function useToaster(message, status) {
    toast(`${message}`, {
      theme: "colored",
      type: `${status}`,
      autoClose: 1000,
      dangerouslyHTMLString: true,
    });
  }

  return {
    useToaster
  }
}
