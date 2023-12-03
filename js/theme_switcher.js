const sw = document.querySelector(".switch_nd");
dark = false;

function switch_theme()
{
    if(dark == false)
    {
        sw.innerHTML = '<img src="img/sun.svg" width="40"></svg>';
        sw.style.backgroundColor = "#fff";
        console.log("Aktualny motyw: ciemny");
        dark = true;
    }
    else
    {
        sw.innerHTML = '<img src="img/moon.svg" width="40"></svg>';
        sw.style.backgroundColor = "#aaa";
        console.log("Aktualny motyw: jasny");
        dark = false;
    }
}