import useAxios from ".";

// ambil seluruh data transaksi_penjualan
export const GetAlltransaksi_penjualan = async () => {
  try {
    const response = await useAxios.get("/transaksi-penjualan", {
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

export const Gettransaksi_penjualanByNama = async (nama) => {
  try {
    const response = await useAxios.get(`/transaksi-penjualan/search/${nama}`, {
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

export const Gettransaksi_penjualanByJabatan = async (jabatan) => {
  try {
    const response = await useAxios.get(`/transaksi-penjualan/${jabatan}`, {
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

export const Gettransaksi_penjualanById = async (id) => {
  try {
    const response = await useAxios.get(`/transaksi-penjualan/${id}`, {
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

export const Createtransaksi_penjualan = async (value) => {
  try {
    const response = await useAxios.post("/transaksi-penjualan", value, {
      headers: {
        "Content-Type": "application/json",
        Authorization: `Bearer ${sessionStorage.getItem("token")}`,
      },
    });
    return response.data;
  } catch (error) {
    throw error.response.data;
  }
};


export const Updatetransaksi_penjualan = async (values) => {
    try {
        const response = await useAxios.put(`/transaksi_penjualan/${values.id}`, values, {
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


export const Deletetransaksi_penjualan = async (id) => {
    await new Promise((resolve) => setTimeout(resolve, 1000));

    try {
        const response = await useAxios.delete(`transaksi_penjualan/${id}`, {
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

export const ResetPasswordtransaksi_penjualan = async (id) => {
    try {
        const response = await useAxios.put(`/transaksi_penjualan/reset-password/${id}`, {}, {
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
