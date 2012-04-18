from test_js import jstest




def test_current_state_initialization():
    def shorthand(test):
        jstest("http://localhost:6543/choose/", test, 
               ignore=["TypeError: 'undefined' is not a function"])

    shorthand("""
VIGIL("CHOOSER.LIVE === true",
      "CHOOSER.STATE.current.field_format == ''",
      "verify that CHOOSER.STATE.current is populated after page load.");
""")


def test_license_interactivity():
    def shorthand(test):
        jstest("http://localhost:6543/choose/", test, 
               ignore=["TypeError: 'undefined' is not a function"])
    
    shorthand("""
WAITFOR("CHOOSER.LIVE === true", function () {
    var inputs = $("input");
    var no_derivs = $("#question_3-7")[0];

    var juri = $("#field_jurisdiction");
    juri[0].selectedIndex = 7;

    no_derivs.checked = true;
    inputs.change();
    
    VIGIL('$("#badge")[0].src == "http://i.creativecommons.org/l/by-nd/2.5/ca/88x31.png"', true, "wait for badge to update to reflect the change radio buttons");
});

    VIGIL('$("#internets .results-preview img")[0].src == "http://i.creativecommons.org/l/by-nd/2.5/ca/88x31.png"', true, "wait for metadata badge to update to reflect the change radio buttons");
""")


def test_metadata_interactivity():
    jstest("http://localhost:6543/choose/", """
WAITFOR("CHOOSER.LIVE === true", function () {
    var title_field = $("input[name=field_worktitle][type=text]");
    title_field[0].value="This is a test.  Do not panic.";
    title_field.change();

    VIGIL (function () { return $("#internets .results-preview>div")[0].innerHTML.indexOf("This is a test.  Do not panic.") > -1;}, true, "Check for correct title in metadata output.");
});
""", ignore=["TypeError: 'undefined' is not a function"])
    jstest("http://localhost:6543/choose/", """
WAITFOR("CHOOSER.LIVE === true", function () {
    var title_field = $("input[name=field_worktitle][type=text]");
    var format_field = $("#format_selection");
    format_field[0].selectedIndex=6;
    title_field[0].value="some other title";
    title_field.change(); // should update them both

    VIGIL (function () { 
        var fragment = $("#internets .results-preview>div")[0].innerHTML;
        var results = true;
        results = results && fragment.indexOf("some other title") > -1;
        results = results && fragment.indexOf("InteractiveResource") > -1;
        return results;
    }, true, "Check for correct title and format in metadata output.");
});
""", ignore=["TypeError: 'undefined' is not a function"])
        

def test_fc_approved():
    def shorthand(test):
        common = """
WAITFOR("CHOOSER.LIVE === true", function () {
    var inputs = $("input");
    var yes_derivs = $("#question_3-5")[0];
    var sa_derivs = $("#question_3-6")[0];
    var no_derivs = $("#question_3-7")[0];
    var yes_comm = $("#question_2-3")[0];
    var no_comm = $("#question_2-4")[0];

    var is_libre = function () {
        var fc_logo = $("#fc_logo_link img")[0].src;
        if (fc_logo.indexOf("fc_questionable.png") > -1) {
            return false;
        }
        else if (fc_logo.indexOf("fc_approved_small.png") > -1) {
            return true;
        }
        else {
            return "busy";
        }
    };

    var set_state = function (derivs, comm) {
        $("#fc_logo_link img")[0].src == "waiting";
        derivs.checked = true;
        comm.checked = true;
        inputs.change();
    };

    var test = function ( derivs, comm, expected, hint ) {
        set_state(derivs, comm);
        var wait = function () {
            return is_libre() !== "busy" && is_libre() == expected;
        }
        VIGIL(wait, true, hint);
    }
    test("""
        jstest("http://localhost:6543/choose/", common+test+");\n});", 
               ignore=["TypeError: 'undefined' is not a function"])

    shorthand("yes_derivs, yes_comm, true, 'is cc-by libre?'")
    shorthand("sa_derivs, yes_comm, true, 'is cc-by-sa libre?'")
    shorthand("no_derivs, yes_comm, false, 'is cc-by-nd non-libre?'")
    shorthand("yes_derivs, no_comm, false, 'is cc-by-nc non-libre?'")
    shorthand("sa_derivs, no_comm, false, 'is cc-by-nc-sa non-libre?'")
    shorthand("no_derivs, no_comm, false, 'is cc-by-nc-nd non-libre?'")


def test_cgi_params():
    jstest("http://localhost:6543/choose/?lang=en&field_derivatives=sa&field_commercial=n&field_jurisdiction=cl&field_iconsize=normal&field_metadata_standard=html%2Brdfa&field_format=Text&field_worktitle=&field_attribute_to_name=Cornelius+Highbrow&field_attribute_to_url=&field_sourceurl=&field_morepermissionsurl=", """

WAITFOR("CHOOSER.LIVE === true", function () {
    var yes_derivs = $("#question_3-5")[0].checked;
    var sa_derivs = $("#question_3-6")[0].checked;
    var no_derivs = $("#question_3-7")[0].checked;
    var yes_comm = $("#question_2-3")[0].checked;
    var no_comm = $("#question_2-4")[0].checked;

    ASSERT (yes_derivs === false, "derivs 'yes' radio button should be false");
    ASSERT (no_derivs === false, "derivs 'no' radio button should be false");
    ASSERT (sa_derivs, "derivs 'sa' radio button should be active");
    ASSERT (yes_comm === false, "commercial should not be 'yes'");
    ASSERT (no_comm, "commercial should be set to 'no'");

    ASSERT ($("#field_jurisdiction option[selected=selected]")[0].value === "cl", "jurisdiction should be Chile");
    
    ASSERT ($("#badge")[0].src === "http://i.creativecommons.org/l/by-nc-sa/3.0/cl/88x31.png", "License badge should be for BY-NC-SA 3.0 Chile");

    var is_libre = function () {
        var fc_logo = $("#fc_logo_link img")[0].src;
        if (fc_logo.indexOf("fc_questionable.png") > -1) {
            return false;
        }
        else if (fc_logo.indexOf("fc_approved_small.png") > -1) {
            return true;
        }
    };

    ASSERT (is_libre() === false, "The license should not be fc approved.");

    ASSERT ($("#internets .results-preview>div")[0].innerHTML === '<a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/cl/"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-sa/3.0/cl/88x31.png"></a><br>This <span xmlns:dct="http://purl.org/dc/terms/" href="http://purl.org/dc/dcmitype/Text" rel="dct:type">work</span> by <span xmlns:cc="http://creativecommons.org/ns#" property="cc:attributionName">Cornelius Highbrow</span> is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/3.0/cl/">Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Chile License</a>.', "Check for correct metadata output.");

});
""", ignore=["TypeError: 'undefined' is not a function"])

   
