const button = document.querySelector("button.add");
const form = document.querySelector("form.add");
const trs = document.querySelectorAll("tr");
const textareas = document.querySelectorAll("textarea");

if (textareas.length) {
    textareas.forEach(textarea => {
        if (textarea.value) textarea.style.height = (textarea.scrollHeight) + "px";
        textarea.addEventListener("input", function () {
            this.style.height = "auto";
            this.style.height = (this.scrollHeight) + "px";
        });
    });
}

button.addEventListener("click", () => {
    form.classList.toggle("show");
    const removeButton = document.querySelector("button.delete");
    if (removeButton) removeButton.click();
    form.querySelectorAll("input").forEach(input => {
        if (input.type != "checkbox") input.value = "";
        else input.checked = false;
    });
    form.querySelectorAll("textarea").forEach(textarea => {
        textarea.value = "";
        textarea.style.height = "auto";
    });
});

trs.forEach(tr => {
    tr.addEventListener("dblclick", () => dblclick(tr));
});

function dblclick(tr) {
    let nextUrl = `${window.location.pathname}?id=${tr.id}`,
        currentUrl = `${window.location.pathname}${window.location.search}`;
    if (currentUrl != nextUrl) window.location.href = nextUrl;
}

function loadImage(event) {
    const file = event.target.files[0];
    const imgOutput = document.getElementById("imgOutput");
    const imgData = document.getElementById("imgData");

    if (!file || !file.type.match("image.*")) return;
    const reader = new FileReader();
    reader.onload = (e) => {
        imgOutput.setAttribute("src", e.target.result);
        imgData.value = e.target.result;
    };
    reader.readAsDataURL(file);
}

function removeImage() {
    const imgOutput = document.getElementById("imgOutput");
    const imgData = document.getElementById("imgData");
    const image = document.getElementById("image");
    image.value = "";
    imgOutput.removeAttribute("src");
    imgData.removeAttribute("value");
}



