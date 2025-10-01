document.getElementById("startDictation").addEventListener("click", function () {
    if (!('webkitSpeechRecognition' in window)) {
        alert("Your browser does not support speech recognition.");
        return;
    }

    const recognition = new webkitSpeechRecognition();
    recognition.lang = "el-GR"; // or "en-US", "fr-FR", etc.
    recognition.continuous = false; // stop after pause
    recognition.interimResults = false; // only final result

    recognition.start();

    recognition.onresult = function (event) {
        const transcript = event.results[0][0].transcript;
        document.getElementById("comment").value += (document.getElementById("comment").value ? " " : "") + transcript;
    };

    recognition.onerror = function (event) {
        console.error("Speech recognition error", event.error);
    };
});
