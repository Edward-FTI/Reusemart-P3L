import useAxios from ".";

export const GetAllHunter = async () => {
  try {
    const response = await useAxios.get("/barangHunter", {
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
