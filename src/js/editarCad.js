// Pegando as cidades

let inputCidade = document.getElementById("cidadeEstagiar") ?? null;
let sugestoes = document.querySelector(".sugestoes");
let cidadesDiv = document.querySelector(".checkboxes");

let qualquerCidade = document.getElementById("qualquerCidade") ?? null;

if(qualquerCidade) {
    qualquerCidade.addEventListener("change", function(e) {
        if(e.target.checked) {
            for (let p of cidadesDiv.children) {
                let input = p.querySelector("input");
                
                if(input != null) input.checked = false;
            }

            e.target.checked = true;
        }

    });
}

if(inputCidade) {
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
}

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

// Pegando as preferencias

let inputCurso = document.getElementById("curso") ?? null;
let preferencias = document.getElementById("preferencias");
let naoPreferencias = document.getElementById("naoPreferencias");

if(inputCurso) {
    inputCurso.addEventListener("change", async function(e) {
        let idCurso = e.target.value;

        try {
            let resposta = await fetch(`./../../buscarPreferencias.php?idCurso=${idCurso}`);

            let data = await resposta.json();

            adicionarPreferencias(preferencias, data, "preferencias[]");
            adicionarPreferencias(naoPreferencias, data, "naoPreferencias[]");

        } catch(e) {
            console.log(e.message);
        }
    });
}

function adicionarPreferencias(divPreferencia, data, lista) {
    divPreferencia.innerHTML = "";

    data.forEach(preferencia => {
        let input = document.createElement("input");

        input.type = "checkbox";
        input.name = lista;
        input.value = preferencia.ID_Preferencia;
        input.addEventListener("change", function() {sincronizarCheckbox(this)});
        sincronizarCheckbox(input);

        let label = document.createElement("label");
        label.textContent = " " + preferencia.Descricao;

        label.prepend(input);

        divPreferencia.appendChild(label);
    });

};

function sincronizarCheckbox(origem) {
    const valor = origem.value;

    const todos = document.querySelectorAll(`.preferencias input[type="checkbox"][value="${valor}"]`);

    todos.forEach(cb => {
        if (cb !== origem) {
            cb.disabled = origem.checked;
        }
    });
}

function inicializarListas() {
    const todos = document.querySelectorAll('.preferencias input[type="checkbox"]');

    todos.forEach(cb => {
        sincronizarCheckbox(cb);
    });
}

document.addEventListener("DOMContentLoaded", () => {
    const checkboxes = document.querySelectorAll('.preferencias input[type="checkbox"]');
    checkboxes.forEach(cb => sincronizarCheckbox(cb));
});

// Modalidades

const checkPresencial = document.getElementById('presencial');
const checkHibrido = document.getElementById('hibrido');
const checkOnline = document.getElementById('online');
const checkTodosModalidade = document.getElementById('todosModalidade');

function selecionar() {
    if(checkTodosModalidade.checked){
        checkPresencial.checked=true;
        checkHibrido.checked=true;
        checkOnline.checked=true;
    } else if(checkTodosModalidade.checked == false) {
        checkPresencial.checked=false;
        checkHibrido.checked=false;
        checkOnline.checked=false;
    } 
}

function verificar(){
    if (checkPresencial.checked && checkHibrido.checked && checkOnline.checked){
        checkTodosModalidade.checked = true;
    } else if (checkPresencial.checked == false || checkHibrido.checked== false || checkOnline.checked == false) {
        checkTodosModalidade.checked = false;
    }
}