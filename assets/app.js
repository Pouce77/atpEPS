import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';


const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))


console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

    const select=document.getElementById("selectEleve");
    const tit=document.getElementById("titre");
    let titre=tit.innerText
    
    select.addEventListener("change", 
        async function(){
            document.getElementById("updateButton").setAttribute("hidden",true);
            document.getElementById("spinner").removeAttribute("hidden")
            const nom=document.getElementById("nomEleve");
            nom.value=select.value;
            let donnee={'nomEleve':select.value,'titre': titre}
            await fetch('https://127.0.0.1:8000/updateEleveTournament', {
                method: 'POST',
                headers: new Headers(),
                body: JSON.stringify(donnee)

            }).then((response) => {
                return response.json();
            }).then((data) => {
                console.log(data);
                let datas=JSON.parse(data);
                console.log(datas);
                document.getElementById("point").value=datas.point;
                document.getElementById("nbreMatch").value=datas.nbreMatch;
                document.getElementById("arbitrage").value=datas.arbitre;
                document.getElementById("goalaverage").value=datas.goalaverage;
                document.getElementById("updateButton").removeAttribute("hidden");
                document.getElementById("spinner").setAttribute("hidden",true);

            }).catch((error) => {
                console.log('Erreur : ' + error.message);
            });
        }
    );

    const backbutton = document.getElementById("backButton");
    backbutton.addEventListener("click", function(){
        document.getElementById("updateButton").setAttribute("hidden",true);
    })