import axios from "axios";

export default function deleteAccount(params) {
    return axios.delete('/api/user/deleteaccount', params);
}