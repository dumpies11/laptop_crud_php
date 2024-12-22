const confirmDelete = (event) => {
    if(!confirm("Are you sure you want to delete the record?")){
        event.preventDefault(true);
    }
}

const confirmEdit = (event) => {
	if(!confirm("Are you sure you want to save changes?")){
		event.preventDefault(true);
	}
}

const aside = document.querySelector(".aside");

const menuButton = document.querySelector(".menu");

menuButton.onclick = function(){
	aside.classList.toggle('active');

	if(aside.classList.contains("active")){
    	document.querySelector(".hamburger").style.display = "none";
		document.querySelector(".close").style.display = "block";
	}else{
		document.querySelector(".hamburger").style.display = "block";
		document.querySelector(".close").style.display = "none";
	}
}

document.getElementsByName()