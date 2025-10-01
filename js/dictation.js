(() => {
    const startBtn = document.getElementById("startDictation");
    const textarea = document.getElementById("comment");
    if (!startBtn || !textarea) return;

    startBtn.addEventListener("click", function () {
        if (!("webkitSpeechRecognition" in window)) {
            alert("Your browser does not support speech recognition.");
            return;
        }

        const recognition = new webkitSpeechRecognition();
        recognition.lang = "el-GR";
        recognition.continuous = false;
        recognition.interimResults = false;

        recognition.start();

        // ---- Helpers ----

        // Replace multi-word commands first (order matters)
        function replaceGreekPunctuationPhrases(text) {
            return text
                // "άνω κάτω τελεία" -> :
                .replace(/(^|[\s"“«'’(])άνω\s+κάτω\s+τελεία(?=$|[\s"”»'’)])/gi, "$1:")
                // "άνω τελεία" -> ·
                .replace(/(^|[\s"“«'’(])άνω\s+τελεία(?=$|[\s"”»'’)])/gi, "$1·")
                // new line phrases -> \n
                .replace(/(^|[\s"“«'’(])(νέα\s+γραμμή|καινούρια\s+γραμμή)(?=$|[\s"”»'’)])/gi, "$1\n");
        }

        // Replace single-word punctuation commands (Greek) without \b
        function replaceGreekPunctuationWords(text) {
            // Match tokens separated by whitespace, preserving spaces
            return text.replace(/([^\S\r\n]+)|(\S+)/g, (m, space, word) => {
                if (space) return space;

                const w = word.toLowerCase();
                switch (w) {
                    case "τελεία": return ".";
                    case "κόμμα": return ",";
                    case "ερωτηματικό": return ";"; // Greek question mark
                    case "θαυμαστικό": return "!";
                    case "παύλα": return "-";
                    case "διπλά":         // "διπλά εισαγωγικά" sometimes split by ASR
                    case "διπλή":
                    case "εισαγωγικά":
                    case "εισαγωγικό": return '"';
                    case "απόστροφος": return "'";
                    case "αποσιωπητικά": return "…";
                    default: return word;
                }
            });
        }

        // Tidy spaces around punctuation (no space before, one space after)
        function tidyPunctuationSpacing(text) {
            // remove space before punctuation
            text = text.replace(/\s+([.,!;:·…])/g, "$1");
            // ensure single space after punctuation when followed by a letter/number
            text = text.replace(/([.,!;:·…])(?=[^\s\n"”»')\].,!?;:])/g, "$1 ");
            // collapse multiple spaces (but keep newlines)
            text = text.replace(/[^\S\n]{2,}/g, " ");
            return text;
        }

        // Capitalize first letter of the text and after . ! ; (Greek question mark)
        function formatSentenceCase(text) {
            // Use Unicode property escapes to catch Greek letters too
            return text.replace(/(^|[.!?;]\s+)(\p{L})/gu, (m, lead, ch) => lead + ch.toUpperCase());
        }

        function postProcessGreekDictation(text) {
            let t = text;
            t = replaceGreekPunctuationPhrases(t);
            t = replaceGreekPunctuationWords(t);
            t = tidyPunctuationSpacing(t);
            t = formatSentenceCase(t);
            return t.trim();
        }

        recognition.onresult = function (event) {
            let transcript = event.results[0][0].transcript || "";
            // console.log("Raw transcript:", transcript);

            const processed = postProcessGreekDictation(transcript);

            // If textarea already has text and doesn't end with a space/newline, add one
            const needsSpace =
                textarea.value &&
                !/[ \t\n]$/.test(textarea.value) &&
                !/^[.,!;:·…]/.test(processed);

            textarea.value += (needsSpace ? " " : "") + processed;
        };

        recognition.onerror = function (event) {
            console.error("Speech recognition error", event.error);
        };
    });
})();