const partyTownButton              = document.getElementById( 'partytown-button' );
const counterPartyTown             = document.getElementById( 'counter-partytown' );
partyTownButton.addEventListener(
	'click',
	() => {
    counterPartyTown.innerText = parseInt( counterPartyTown.innerText ) + 1;
	}
);
