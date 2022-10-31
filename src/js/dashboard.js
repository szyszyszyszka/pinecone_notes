$(document).ready(function(){

    //Listening for a click  on button with id "log_out", performing loggingOut function (line 48) if click occurs
    document.getElementById("log_out").addEventListener('click', loggingOut);

    //Enabling autogrowing verticaly based on text length for all note inputs (textarea tags)
    $(".notes_input").autogrow({vertical: true, horizontal: false});
});

//Checking if submited new category is not empty - if it is returns false (form is not submited), if it isn't it returns true (form is submited)
function addingCategoryValidation()
{
    let new_category = document.getElementById("add_category_input").value;
    if(new_category == "" || new_category == null || new_category == undefined)
        return false;
    else return true;
}

//Checking if submited new note is not empty - if it is it returns false (form is not submited), if it isn't it returns true (form is submited)
function addingNoteValidation()
{
    let new_note = document.getElementsByName('new_note')[0].value;
    if(new_note == "" || new_note == null || new_note == undefined)
        return false;
    else return true;
}

//Checking if user really wants to delete given category - if it is, returns false (form is not submited), if it isn't, returns true (form is submited)
function deletingCategoryConfimation()
{
    let category = document.getElementById('delete_category_button').value;
    let yn = confirm(`Are you sure you want to delete '${category}' category?\nAll notes associated with this category will be deleted!`);
    if(yn == true)
        return true
    else return false;
}

//Checking if user really wants to modify/delete given note - if it is, returns false (form is not submited), if it isn't, returns true (form is submited)
function changingNoteConfirmation()
{
    let yn = confirm(`Are you sure you want to modify/delete note?`);
    if(yn == true)
        return true
    else return false;
}

//Redirecting to a script that logs out the user
function loggingOut()
{
    let yn = confirm("Are you sure you wat to log out?\nAll unsaved changes will be lost!");
    if(yn == true)
        window.location = window.location = "log_out.php";
}