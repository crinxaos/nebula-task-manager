let activeToast = null;

function showToast(msg, type = "success") {
    if (activeToast) activeToast.remove();

    const t = document.createElement("div");
    t.className = "toast " + (type === "error" ? "error" : "");
    t.innerText = msg;

    document.body.appendChild(t);

    setTimeout(() => t.classList.add("show"), 100);

    setTimeout(() => {
        t.classList.remove("show");
        setTimeout(() => t.remove(), 400);
    }, 3000);

    activeToast = t;
}