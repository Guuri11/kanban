import axios from "axios";

export default function deleteColumn(id, requestOptions) {
    return axios.delete(`/api/column/delete/${id}`, requestOptions);
}