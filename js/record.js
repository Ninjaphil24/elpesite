let mediaRecorder;
let audioChunks = [];

const recordBtn = document.getElementById("recordBtn");
const audioPreview = document.getElementById("audioPreview");
const audioFileInput = document.getElementById("audioFile");
const uploadBtn = document.getElementById("uploadBtn");

// Start recording on press
recordBtn.addEventListener("mousedown", startRecording);
recordBtn.addEventListener("touchstart", startRecording);

// Stop recording on release
recordBtn.addEventListener("mouseup", stopRecording);
recordBtn.addEventListener("mouseleave", stopRecording);
recordBtn.addEventListener("touchend", stopRecording);

async function startRecording(e) {
    e.preventDefault();
    if (mediaRecorder && mediaRecorder.state === "recording") return;

    let stream = await navigator.mediaDevices.getUserMedia({ audio: true });
    mediaRecorder = new MediaRecorder(stream);
    audioChunks = [];

    mediaRecorder.ondataavailable = event => audioChunks.push(event.data);
    mediaRecorder.start();
    recordBtn.innerText = "⏺ Recording... Release to Stop";
}

function stopRecording(e) {
    e.preventDefault();
    if (!mediaRecorder || mediaRecorder.state !== "recording") return;

    mediaRecorder.stop();
    recordBtn.innerText = "🎙 Hold to Record";

    mediaRecorder.onstop = () => {
        const audioBlob = new Blob(audioChunks, { type: "audio/webm" });
        const audioUrl = URL.createObjectURL(audioBlob);

        // Show preview
        audioPreview.src = audioUrl;
        audioPreview.style.display = "block";

        // Show upload button now that a file exists
        uploadBtn.style.display = "inline-block";

        // Prepare file for upload
        const file = new File([audioBlob], "comment.webm", { type: "audio/webm" });
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        audioFileInput.files = dataTransfer.files;
    };
}
