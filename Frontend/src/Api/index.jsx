import axios from "axios";

export const BASE_URL = 'http://192.168.132.242:8000'; // Proxy akan mengarahkan permintaan ke backend


export const getThumbnail = (thumbnail) => {
    return `${BASE_URL}/storage/contents/${thumbnail}`;
}

const useAxios = axios.create({
    baseURL: `${BASE_URL}/api`,
});

export default useAxios;
