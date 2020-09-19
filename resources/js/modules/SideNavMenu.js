class SideNavMenu{
	constructor(){
		this.menuIcon = document.querySelector(".sidebar__menu__icon");
		this.menuContent = document.querySelector(".sidebar__menu");
		this.sidebarNav = document.querySelector(".sidebar__nav");
		this.mainLeftContent = document.querySelector(".content__main__left");
		this.mainRightContent = document.querySelector(".content__main__right");
		this.events();
	}

	events(){
		this.menuIcon.addEventListener("click", ()=>this.toggleTheMenu())
	}

	toggleTheMenu(){
		this.menuContent.classList.toggle("sidebar__menu--visible");
		this.mainLeftContent.classList.toggle("content__main__left--expanded");
		this.sidebarNav.classList.toggle("sidebar__nav--expanded");
		this.mainRightContent.classList.toggle("content__main__right--expanded");
		this.menuIcon.classList.toggle("sidebar__menu__icon--close-x");
	}
}

export default SideNavMenu;