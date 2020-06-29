import axios from "axios";

export default function register(requestOptions) {
   return axios.post('/api/user/register', requestOptions)
}