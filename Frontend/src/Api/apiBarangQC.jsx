import { toast } from "sonner";
import useAxios from ".";


// ambil seluruh data barang
export const GetAllBarang = async () => {
    try {
        const response = await useAxios.get('/barang-qc', {
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${sessionStorage.getItem("token")}`
            },
        });
        return response.data.data;
    }
    catch (error) {
        throw error.response.data;
    }
}

export const CreateBarang = async (value) => {
    try {
        const formData = new FormData();

        for (const key in value) {
            formData.append(key, value[key]);
        }

        const response = await useAxios.post("/barang-qc", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
                Authorization: `Bearer ${sessionStorage.getItem("token")}`,
            },
        });
        return response.data.data;
    }
    catch (error) {
        console.error(error);
        throw error.response?.data || error;
    }
};

export const UpdateBarang = async (values) => {
    try {
        const formData = new FormData();

        for (const key in values) {
            formData.append(key, values[key]);
        }

        formData.append('_method', 'PUT'); // Tambahkan ini agar backend Laravel mengenali override PUT

        const response = await useAxios.post(`/barang-qc/${values.id}`, formData, {
            headers: {
                "Content-Type": "multipart/form-data",
                Authorization: `Bearer ${sessionStorage.getItem("token")}`,
            },
        });

        return response.data.data;
    } catch (error) {
        console.error(error);
        throw error.response?.data || error;
    }
};


// export const UpdateBarang = async (values) => {
//     try {
//         const formData = new FormData();

//         for (const key in values) {
//             formData.append(key, values[key]);
//         }

//         const response = await useAxios.post(`/barang-qc/${values.id}`, formData, {
//             headers: {
//                 "Content-Type": "multipart/form-data",
//                 Authorization: `Bearer ${sessionStorage.getItem("token")}`,
//             },
//         });
//         return response.data.data;
//     }
//     catch (error) {
//         console.error(error);
//         throw error.response?.data || error;
//     }
// };


// export const UpdateBarang = async (values) => {
//     try {
//         const response = await useAxios.put(`/barang-qc/${values.id}`, values, {
//             headers: {
//                 // "Content-Type": "application/json",
//                 "Content-Type": "multipart/form-data",
//                 Authorization: `Bearer ${sessionStorage.getItem("token")}`,
//             },
//         });
//         return response.data;
//     }
//     catch (error) {
//         throw error.response.data;
//     }
// }


export const DeleteBarang = async (id) => {
    await new Promise((resolve) => setTimeout(resolve, 1000));

    try {
        const response = await useAxios.delete(`barang-qc/${id}`, {
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${sessionStorage.getItem("token")}`,
            },
        });
        toast.success('Berhasil menghapus');
        return response.data;
    }
    catch (error) {
        throw error.response.data;
    }
}

