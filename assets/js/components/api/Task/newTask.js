import axios from "axios";

export default function newTask(params) {
    axios.post('/api/task/new', params).then(res => console.log(res))
        .catch(e=> console.log(e.response));
}