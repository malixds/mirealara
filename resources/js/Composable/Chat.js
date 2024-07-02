import {ref} from "vue";
import axios from "axios";

export default function useChat() {
    const messages = ref([]);
    const errors = ref([]);
    const getMessage = async () => {
        await axios.get('/chat/messages').then((response) => {
            messages.value = response.data;
        })
    }

    const addMessage = async (form) => {
        console.log('form with message', form, form.value, form.message) // отрабатывае
        errors.value = [];
        try {
            await axios.post('/chat/send', form).then((response) => {
                messages.value.push(response.data);
                console.log('response data', response);
            })
        } catch (e) {
            console.log('Ошибка', e.message, e.response, e.data, e.value)
            if (e.response.status === 422) {
                errors.value = e.response.data.errors
            }
        }

    }

    return {
        messages,
        errors,
        useChat,
        addMessage,
        getMessage
    }
}