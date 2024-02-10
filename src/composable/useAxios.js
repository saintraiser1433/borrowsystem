import axios from "axios";
export function useAxios(url) {
    const http = axios.create({
        baseURL: "http://localhost:8383/borrowing-api/inventory/",
        headers: {
            "Content-type": "application/json",
        },
    });

   async function fetchData() {
        return await http.get(`${url}?action=GET`);
    }

    async function addData(post) {
        return await http.post(`${url}?action=POST`, post);
    }

    async function updateData(post) {
         return await http.post(`${url}?action=PUT`, post);
    }

    async function deleteData(id) {
        return await http.delete(`${url}?action=DELETE`, {
            params: {
                id: id,
            },
        });
    }

    return {
        fetchData,
        addData,
        updateData,
        deleteData
    }

    // function getDataById(id) {
    //     return http.get(`/data/${id}`);
    // }


}



