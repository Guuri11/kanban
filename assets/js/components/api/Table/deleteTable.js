import axios from "axios";

export default function deleteTable(id, requestOptions) {
    return axios.delete(`/api/table/delete/${id}`, requestOptions)
}