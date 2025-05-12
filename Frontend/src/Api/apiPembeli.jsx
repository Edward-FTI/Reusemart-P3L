import useAxios from ".";

// ambil seluruh data pembeli
export const GetAllpembeli = async () => {
    try {
        const response = await useAxios.get('/pembeli', {
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


export const GetpembeliByNama = async (nama) => {
    try {
        const response = await useAxios.get(`/pembeli/search/${nama}`, {
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


export const GetpembeliByJabatan = async (jabatan) => {
    try {
        const response = await useAxios.get(`/pembeli/${jabatan}`, {
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${sessionStorage.getItem("token")}`,
            },
        });
        return response.data.data;
    }
    catch (error) {
        throw error.response.data
    }
}


export const GetpembeliById = async (id) => {
    try {
        const response = await useAxios.get(`/pembeli/${id}`, {
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${sessionStorage.getItem("token")}`,
            },
        });
        return response.data.data
    }
    catch (error) {
        throw error.response.data;
    }
}


export const Createpembeli = async (value) => {
    try {
        const response = await useAxios.post("/pembeli", value, {
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${sessionStorage.getItem("token")}`,
            },
        });
        return response.data;
    }
    catch (error) {
        throw error.response.data;
    }
}


export const Updatepembeli = async (values) => {
    try {
        const response = await useAxios.put(`/pembeli/${values.id}`, values, {
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${sessionStorage.getItem("token")}`,
            },
        });
        return response.data;
    }
    catch (error) {
        throw error.response.data;
    }
}


export const Deletepembeli = async (id) => {
    await new Promise((resolve) => setTimeout(resolve, 1000));

    try {
        const response = await useAxios.delete(`pembeli/${id}`, {
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${sessionStorage.getItem("token")}`,
            },
        });
        return response.data;
    }
    catch (error) {
        return error.response.data;
    }
}

export const ResetPasswordpembeli = async (id) => {
    try {
        const response = await useAxios.put(`/pembeli/reset-password/${id}`, {}, {
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${sessionStorage.getItem("token")}`,
            },
        });
        return response.data;
    } catch (error) {
        throw error.response.data;
    }
}
