<template>
<!--    {{messages}}-->
    {{user}}
    {{user.id}}
    <div class="chat-message" v-for="message in messages">
        <div class="flex items-end" :class="{'justify-end': message.user_id !== 10}">
            <div class="flex flex-col space-y-2 text-xs max-w-xs mx-2 order-2" :class="{'items-end': message.user_id !== 10, 'items-start': message.user_id === 10}">
                <div>
                    <span class="px-4 py-2 rounded-lg inline-block"
                          :class="{'rounded-br-none bg-blue-600 text-white': message.user_id !== 10, 'rounded-bl-none bg-gray-300 text-gray-600': message.user_id === 10}"
                    >
                        {{ message.message}}
<!--                        {{ message.message.message}}-->
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import useChat from "@/Composable/Chat.js";
import {onMounted} from 'vue';

export default {
    name: 'ChatMessages',
    props: {
        user: {
            required: true,
            type: Object
        }
    },
    setup() {
        const {messages, getMessage} = useChat()

        onMounted(async () => {
            await getMessage();
            console.log('Messages after onMounted:', messages.value);
        });

        Echo.private('chat')
            .listen('MessageSent', (e) => {
                console.log('messagesent', e)
                messages.value.push({
                    message: e.message,
                    user: e.user
                });
            });
        console.log('messages333', messages);

        return {
            messages
        }
    }
};
</script>

<style scoped>

</style>
