import { post } from "../modules/http.js";
new Vue({
    el: "#TalkieApp",
    data() {
        return {
            error: null,
            result: null,
            isListening: false,
            mediaRecorder: null,
            audioChunks: []
        };
    },

    methods:{
        async startRecording() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                this.mediaRecorder = new MediaRecorder(stream);
                this.audioChunks = [];

                this.mediaRecorder.ondataavailable = (event) => {
                    console.log("chunk data",event.data);
                    this.audioChunks.push(event.data);
                };

                this.mediaRecorder.onstop = this.sendAudio;

                this.mediaRecorder.start();
                this.isListening = true;
            } catch (error) {
                this.error = "Erreur d'accès au microphone : " + error.message;
                console.error(this.error);
            }
        },

        stopRecording() {
            if (this.mediaRecorder) {
                this.mediaRecorder.stop();
                this.isListening = false;
            }
        },

        async sendAudio() {
            const currentUserId = document.querySelector('meta[name="user-id"]').getAttribute('content');
            const audioBlob = new Blob(this.audioChunks, { type: 'audio/wav' });
            const formData = new FormData();

            formData.append('audio', audioBlob, 'audio.wav');
            formData.append('user_id', currentUserId);
            formData.append('sender', 'web');
            try {
                const { data, status } = await post('/send.talk', formData);
                if(data.status !== undefined){
                    new Audio("assets/audios/walkie-talkie-off.mp3").play();
                }
                console.log(data);
            } catch (error) {
                console.error('Erreur lors de l\'envoi:', error);
            }
        }
    }
});
