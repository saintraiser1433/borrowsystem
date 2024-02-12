import axios from "axios";
import { development } from "@/constants/server";
/**
 * Basic HTTP response status code
 * Success response status code
 * 201 and 200
 * Error code response status code
 * 400,500,401,422 and 403
 */

export async function useAxios({ ...args }) {
  try {
    const response = await axios({
      method: args.method,
      data: args.data,
      params: args.params,
      headers: {
        ...args.header,
      },
      baseURL: development.baseUrl + args.api,
    });

    return { ok: true, data: response.data };
  } catch (error) {
    return { ok: false, error: error.message };
  }
}
