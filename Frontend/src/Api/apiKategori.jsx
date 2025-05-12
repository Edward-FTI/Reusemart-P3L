import useAxios from ".";

export const GetAllKategori = async () => {
    try {
        const response = await useAxios.get('/kategori', {
            headers: {
                "Content-Type": "application/json",
                Authorization: `Bearer ${sessionStorage.getItem("token")}`,
            },
        });
        return response.data.data;
    } catch (error) {
        console.error("Error fetching kategori:", error.response || error.message);
        throw error.response?.data || error.message;
    }
};