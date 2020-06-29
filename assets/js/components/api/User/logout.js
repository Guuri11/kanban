import axios from "axios";

export default function logout() {
    axios.get('/api/user/logout');
}