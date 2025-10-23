let olhoImage = document.querySelector(".senha figure img");
let senhaInput = document.querySelector(".senha input");

olhoImage.addEventListener("click", function() {
    if(senhaInput.type == "text") {
        senhaInput.type = "password";

        olhoImage.src = "./src/images/olhoaberto.png";
        olhoImage.alt = "Olho Aberto";
    } else {
        senhaInput.type = "text";

        olhoImage.src = "./src/images/olhofechado.png";
        olhoImage.alt = "Olho Fechado";
    }
});