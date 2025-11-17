function openAgreementModal(){
    document.getElementById('agreementModal').classList.remove('hidden');
}

function closeAgreementModal() {
    document.getElementById('agreementModal').classList.add('hidden');
}   

function checkAgreement() {
    const checkBox = document.getElementById('checkAgree');
    const signUpButton = document.getElementById('sign_up_button');
    signUpButton.disabled = !checkBox.checked;
}

function unCheckAgreement(){

    const checkbox = document.getElementById('checkAgree');
    const signUpButton = document.getElementById('sign_up_button');
    checkbox.checked = false;
    signUpButton.disabled = true;
}

function validateAgreement(){
    const checkbox = document.getElementById('checkAgree');
    if(!checkbox.checked){
        alert("You must agree to the terms and conditions to sign up.");
        return false;
    }
}
