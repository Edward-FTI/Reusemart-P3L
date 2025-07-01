// use Axios from 'axios';
import useAxios from ".";
export const GetKurirBarang = async () => {
  try {
    const response = await useAxios.get("/kurir-barang", {
      headers: {
        "Content-Type": "application/json",
        Authorization: `Bearer ${sessionStorage.getItem("token")}`,
      },
    });
    return response.data.data;
  } catch (error) {
    throw error.response.data;
  }
};

export const GetDisiapkan = async () => {
  try {
    const response = await useAxios.get("/transaksi-proses", {
      headers: {
        "Content-Type": "application/json",
        Authorization: `Bearer ${sessionStorage.getItem("token")}`,
      },
    });
    return response.data.data;
  } catch (error) {
    throw error.response.data;
  }
};

export const batal = async (id) => {
  try {
    const response = await useAxios.post(
      `/transaksi-batal/${id}`,
      {}, // Body kosong
      {
        headers: {
          "Content-Type": "application/json",
          Authorization: `Bearer ${sessionStorage.getItem("token")}`,
        },
      }
    );
    return response.data.data;
  } catch (error) {
    console.error("Gagal membatalkan:", error.response?.data || error.message);
    throw error.response?.data || error;
  }
};

