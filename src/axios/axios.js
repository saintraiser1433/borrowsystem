import axios from 'axios';
export async function api({ ...args }) {
    try {
        const response = await axios({
            method: args.method,
            data: args.data,
            params: args.params,
            headers: {
                ...args.header,
            },
            baseURL: 'http://localhost:8383/borrowing-api/' + args.api
        })

        return { ok: true, data: response.data }
    } catch (error) {
        return { ok: false, error: error.response }

    }
}