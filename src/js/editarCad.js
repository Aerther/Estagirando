let inputCidade = document.getElementById("cidadeEstagiar");
let sugestoes = document.querySelector(".sugestoes");
let cidadesDiv = document.querySelector(".checkboxes");

let qualquerCidade = document.getElementById("qualquerCidade");

qualquerCidade.addEventListener("change", function(e) {
    if(e.target.checked) {
        for (let p of cidadesDiv.children) {
            let input = p.querySelector("input");
            
            if(input != null) input.checked = false;
        }

        e.target.checked = true;
    }

});

inputCidade.addEventListener("input", async function(e) {
    let [cidade, uf = ""] = e.target.value.split(",").map(s => s.trim());

    if(cidade.length <= 2) {
        sugestoes.innerHTML = "";

        return;
    };

    try {
        let resposta = await fetch(`./../../buscarCidade.php?nome=${encodeURIComponent(cidade)}&uf=${uf}`);

        let data = await resposta.json();

        sugestoes.innerHTML = "";

        data.forEach(cidade => {
            let p = document.createElement("p");

            p.textContent = `${cidade.Nome}, ${cidade.UF}`;
            p.dataset.id = cidade.ID_Cidade;
            p.classList.add("item-sugestao");
            p.addEventListener("click",  () => adicionarCidade(p));

            sugestoes.appendChild(p);
        });

    } catch(e) {
        console.log(e.message);
    }
});

function resetarQualquerCidade() {
    qualquerCidade.checked = false;
}

function adicionarCidade(e) {
    resetarQualquerCidade();

    let input = document.createElement("input");

    input.type = "checkbox";
    input.name = "cidadesEstagiar[]";
    input.value = e.dataset.id;
    input.checked = true;
    input.addEventListener("change", resetarQualquerCidade);

    let label = document.createElement("label");
    label.textContent = " " + e.textContent;

    label.prepend(input);

    for (let p of cidadesDiv.children) {
        if(p.textContent === label.textContent) {
            p.querySelector("input").checked = true;

            return;
        }
    }

    cidadesDiv.appendChild(label);
}