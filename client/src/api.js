import axios from "axios";

const API_BASE_URL = "http://127.0.0.1:8000/api"; // Laravel API URL

export const fetchProducts = async () => {
  const response = await axios.get(`${API_BASE_URL}/products`);
  return response.data;
};
