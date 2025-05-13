import axios from 'axios';

import useAxios from '.';

export const GetAllPenitip = async () => {
    try {
        const response = await useAxios.get('/penitip', {
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${sessionStorage.getItem("token")}`,
            },
        });
        return response.data.data;
    }
    catch (error) {
        throw error.response.data;
    }
}

export const GetPenitipByNama = async (nama) => {
    try {
        const response = await useAxios.get(`/penitip/search/${nama}`, {
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${sessionStorage.getItem("token")}`,
            },
        });
        return response.data.data;
    }
    catch (error) {
        throw error.response.data;
    }
}

export const GetPenitipById = async (id) => {
    try {
        const response = await useAxios.get(`/penitip/${id}`, {
            headers: {
                // "Content-Type": "application/json",
                Authorization: `Bearer ${sessionStorage.getItem("token")}`,
            },
        });
        return response.data.data
    }
    catch (error) {
        throw error.response.data;
    }
}

export const CreatePenitip = async (data) => {
    try {
        const response = await useAxios.post('/penitip', data, {
            headers: {
                // "Content-Type": "application/json",
                Authorization: `Bearer ${sessionStorage.getItem("token")}`,
            },
        });
        return response.data.data;
    }
    catch (error) {
        throw error.response.data;
    }
}

// export const UpdatePenitip = async (id, data) => {
//     try {
//         const response = await useAxios.put(`/penitip/${id}`, data, {
//             headers: {
//                 "Content-Type": "application/json",
//                 Authorization: `Bearer ${sessionStorage.getItem("token")}`,
//             },
//         });
//         return response.data.data;
//     }
//     catch (error) {
//         throw error.response.data;
//     }
// }

export const UpdatePenitip = async (id, data) => {
  const response = await axios.put(`/penitip/${id}`, data, {
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  });
  return response.data;
};

export const DeletePenitip = async (id) => {
    try {
        const response = await useAxios.delete(`/penitip/${id}`, {
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${sessionStorage.getItem("token")}`,
            },
        });
        return response.data.data;
    }
    catch (error) {
        throw error.response.data;
    }
}
