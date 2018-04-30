<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       Codewalker Institute
 * @since      1.0.0
 *
 * @package    Action_texter
 * @subpackage Action_texter/public/partials
 */
?>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"   integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="   crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
  <script
  src="https://cdn.jsdelivr.net/npm/moment@2.21.0/moment.min.js"> </script>
<style>

  body {
    font-family: sans-serif;
  }

  .tester button, #phone {
    width: 138px;
    margin: 10px;
  }
  .bottom {
    margin-bottom: 20px;
	}

	#tags {
		width: 260px;
	}

	.sender button {
		width:390px;
	}

	button {
		height: 30px;
    cursor: pointer;
	}

  #dialog {
    height: 100%;
    width: 100%;
    background-color: white;
    position: absolute;
    left: 0;
    top: 0;
  }

  #left {
    width: 50%;
    float: left;
  }

  #right {
    width: 45%;
    display: inline;
    float: left;
  }

  #right button {
    border: 1px solid black;
    font-weight: bold;
  }

  #create_new {
    display: inline;
    font-size: 10px;
  }
  #existing_flows {
    background-color: rgb(214, 229, 245);
    width: 60%;
    display: inline-block;
    float: right;
    padding: 1%;
    position: relative;
  }

  #flow_list button {
    background-color: blue;
    color: white;
    border: 1px white solid;
    right: 0;
    float: right;
  }
  #flow_form {
    display: none;
    height: 100%;
    width: 100%;
    position: absolute;
    left: 0;
    top: 0;
    background-color: white;
    padding: 3%;
  }

  #flow_form input {
    width: 60px;
    margin-left: 10px;
  }
</style>
<main>
  <section id="left">
    <h2> BROADCAST</h2>
    <h4>AN Texter with Twillio</h4>
    <img id="loadingTags" src="<?php echo plugins_url( 'images/loading_spinner.gif', __FILE__ ); ?>" />
    <div>
      <label for="ID Tags">Action Network Tag</label>
      <select class="js-example-basic-single bottom" name="tags" id="tags" onchange="getTaggingsCount(this)">
          <option disabled selected>Select a Tag</option>
      </select>
    </div>
    <div>
        <span id="recipientTotal">Total recipients: 0</span>
    </div>
    <div class="bottom">
      <label for="message">Message</label>
      <textarea id="message" name="message" rows="4" cols="50"></textarea>
    </div>
    <div class="tester">
      <label for="phone">Test Phone#:</label>
      <input id="phone" name="phone">
      <button type="button" onclick="tester()" >test</button>
    </div>
    <div class="sender">
      <button id="sendButton" type="button" onclick="sender()" disabled >send</button>
    </div>
    <div id="response">HI
    </div>
    <img id="sendingTexts" src="<?php echo plugins_url( 'images/loading_spinner.gif', __FILE__ ); ?>" />
  </section>
  <section id="right">
    <h2> Sign Up Flows </h2>
    <div>
      <div id="create_new">
        <button id="newFlow_button"> Create new Flow </button>
      </div>
      <div id="existing_flows">
        <h4> Existing Flows </h4>
        <div id="flow_list">
          <!-- <p> JOIN  <button> EDIT </button> </p>
          <p> EPA Petition  <button> EDIT </button> </p>
          <p> House Party sign up  <button> EDIT </button> </p> -->
        </div>
        <div id="flow_form">  <button id="closeForm"> X </button>
          <button id="addNewField"> Add new field </button>
          <form id="form">
            <p> <strong> Select Action Network form to sync flow to </strong> <select name="whichForm" id="formSelect"><option value="placeholder"> Placeholder </option> </select> </p>
            <hr />
            <p> What is the name of this flow? <input type="text" name="title" id="titleInput"> </input></p>
            <p> Enter key word to start flow <input type="text" name="keyWord" id="keyWordInput"> </input></p>
            <div id="requestDiv">
              <p> Request for field:
                <select name="field1" id="fieldSelect1">
                   <option value="firstName"> First Name</option>
                   <option value="lastName"> Last Name</option>
                   <option value="email"> E-mail</option>
                   <option value="zipCode"> Zip Code</option>
                 </select>
                 <input type="text" name="field1Value" id="fieldInput1">
                 </input>
              </p>

            </div>
            <p> THANK YOU TEXT <input type="text" name="thankYou" id="thankYouInput"> </input></p>
            <input type="submit"> </input>
          </form>
        </div>
      </div>
    </div>
  </section>
  <?php if (!get_field( 'action_network_api_key', 'user_'. get_current_user_id()) || !get_field( 'twilio_account_sid', 'user_'.     get_current_user_id()) || !get_field( 'twilio_auth_token', 'user_'. get_current_user_id()) || !get_field( 'twilio_from_number', 'user_'. get_current_user_id()) || !get_field('action_texts_api_key', 'user_'. get_current_user_id()) ): ?>
    <div id="dialog"><br><br> <br><br>
      <p> You are missing one or more of your configuration keys. Please follow the link below to insert them.</p>
      <p> <a href='profile.php#your-profile'>Navigate here to add config keys</a> </p>
    </div>

  <?php else: ?>
  <?php  endif ?>
</main>
<script>
  const message = document.getElementById("message").value;
  const tag = document.getElementById("tags").value;
  const phone = document.getElementById("phone").value;
  const count = 0;
  const total = 0;
  const ANAdress = "https://actionnetwork.org/api/v2/";
  const sendServer = ajaxurl;
  const ANForms = "https://actionnetwork.org/api/v2/forms";
  const theTagId = "";
  const flowBase =   {
    "title": 'Default Title',
    "activationKeyword": 'Default Keyword',
    "foreignPath": "https://actionnetwork.org/forms/join-from-sms/answers",
    "foreignMethod": "POST",
    "steps": [
      {
        "prompt": "What is your first name?",
        "foreignName": "firstName"
      }, {
        "prompt": "Thank You sir/mam"
      }
    ]
  };

  let currentFlow = flowBase;
  let activeFlowIndex = null;

  document.addEventListener("DOMContentLoaded", function() {
    $("#sendingTexts").hide();
    fetchTags();
    fetchBatches();
    getFlows();
    fetchForms();
    newFlowEventHandler();
    attachSubmitAndCloseForm();
    attachFormChangeEventHandlers();
  });


  //////////////////////////////////////////////////////////////////////////////////////////////
  // Get tag categories from Action Network and add to tag_id dropdown
  function fetchTags() {
      var xhttp = new XMLHttpRequest();
      xhttp.addEventListener("load", getTags);
      xhttp.open("GET", ajaxurl + "?action=fetch_tags", true);
      xhttp.send();
  }

  function getTags() {
      var resp = JSON.parse(this.responseText);
      var tags = resp._embedded["osdi:tags"];

      for (var x = 0; x < tags.length; x++) {
          var ref = tags[x]['_links']['self']['href'].split('/');
          addToSelect(tags[x].name, ref[ref.length - 1] );
      }
      $("#loadingTags").hide();
  }

  function fetchBatches() {
    var xhttp = new XMLHttpRequest();
    xhttp.addEventListener("load", getBatches);
    xhttp.open("GET", ajaxurl + "?action=fetch_batches", true);
    xhttp.send();
  }

  function getBatches() {
    var resp = JSON.parse(this.responseText);
    var htmlStrings = [];

    for (var i = 0; i < resp.length; i++) {
        var dateObject = new Date(resp[i].finish);
        var momentObject = moment(dateObject);
        var momentString = momentObject.format('MMMM Do YYYY, h:mm:ss a')
      var html  = '<div><h4> Batch Number #' + (i + 1) + '</h4> <p><b> Time sent:</b> ' + momentString + '</p> <p><b> Message sent was:</b> ' + resp[i].message + '</p><p><b>Messages sent:</b> ' + resp[i].messagesSent + '</div><hr />';
      htmlStrings.push(html);
    }
    var htmlToAdd = '<h2> Broadcast Replies </h2' + htmlStrings.join();
    $('#response').html(htmlToAdd);

  }

  function newFlowEventHandler() {

    $("#newFlow_button").click(function() {
      currentFlow = flowBase;
      $('#form')[0].reset();
      $("#flow_form").toggle();
      $('#addNewField').on('click', function() {
        const currentStepLength = currentFlow.steps.length;
        const newStepIndex = currentStepLength - 1;
        currentFlow.steps.splice(newStepIndex, 0, {
          prompt: null,
          foreignName: 'default'
        })

        const p = document.createElement('p');
        p.id = 'newP';
        const select = document.createElement('select');
        const option1 = document.createElement('option');
        option1.value = 'firstName';
        option1.text = 'First name';
        const option2 = document.createElement('option');
        option2.value = 'lastName';
        option2.text = 'Last name';
        const option3 = document.createElement('option');
        option3.value = 'email';
        option3.text = 'E-mail';
        const option4 = document.createElement('option');
        option4.value = 'zipCode';
        option4.text = 'Zip Code';
        const input = document.createElement('input', {type: 'text'});
        const deleteButton = document.createElement('button');
        deleteButton.append('Delete');
        deleteButton.addEventListener('click', function(){
          //this DELETES THAT INDEX FROM THE CURRENT FLOW
          //5 ELEMENTS --3 created
          currentFlow.steps.splice(newStepIndex, 1);
          deleteButton.parentNode.parentNode.removeChild(p);
        });

        select.appendChild(option1);
        select.appendChild(option2);
        select.appendChild(option3);
        select.appendChild(option4);

        select.onchange = function() {
          currentFlow.steps[newStepIndex].foreignName = select.value;
        }

        p.append('Request for field: ');
        p.append(select);
        p.append(input);
        p.append(deleteButton);
        input.oninput = function(event) {
          currentFlow.steps[newStepIndex].prompt = input.value;
        };
        p.onclick = function(event){
        }
        $('#requestDiv').append(p);
      });
    });
  }

  function getFlows() {
    var xhttp = new XMLHttpRequest();
    xhttp.addEventListener("load", displayFlows);
    xhttp.open("GET", ajaxurl + "?action=get_flows", true);
    xhttp.send();
  }

  function displayFlows() {
    let flows = JSON.parse(this.response);
    let htmlArray = [];
    let activeCount = 0;
    for (let i = 0; i < flows.length; i++) {
      if (flows[i].active) {
        activeFlowIndex = i;
        activeCount++;
      }
      let flowPElement = document.createElement('p');
      flowPElement.append(flows[i].title);

      let flowRadioElement = document.createElement('input');
      flowRadioElement.type = 'radio';
      flowRadioElement.name = "isActiveFlow";
      flowRadioElement.value = `isActive${i}`;
      flowRadioElement.id = `radio${i}`;
      flowRadioElement.className = 'radioButton';
      if (activeFlowIndex === i) {
        flowRadioElement.checked = 'checked';
      }
      flowRadioElement.addEventListener('change', function(event) {
        const targetFlow = flows[i];
        //This is to handle existing active flow requirement to be changed
        if (activeFlowIndex !== null && activeFlowIndex !== i) {
          flows[activeFlowIndex].active = null;
          putFlow(flows[activeFlowIndex], true, true);
          activeFlowIndex = i;
          flows[i].active = true;
          putFlow(flows[i], true)

        } else if (activeFlowIndex === null) {
          flows[i].active = true;
          activeFlowIndex = i;
          putFlow(flows[i], true);
        }
      });
      flowPElement.append(flowRadioElement);

      let flowButtonElement = document.createElement('button');
      flowButtonElement.className = 'editButton';
      flowButtonElement.append('EDIT');
      flowButtonElement.id = `flow${i}`;
      flowButtonElement.onclick = function(event) {
        let id = this.id;
        let sliced = id.split('w');
        let number = parseInt(sliced[1]);
        currentFlow = flows[number];
        prepopulateForm();
        $('#flow_form').toggle();
      }

      flowPElement.append(flowButtonElement);
      htmlArray.push(flowPElement);
    }
    $('#flow_list').html();
    $('#flow_list').append(htmlArray);

  }

  function prepopulateForm() {
    if (currentFlow) {
      $('#keyWordInput').val(currentFlow.activationKeyword);
      $('#titleInput').val(currentFlow.title);
      $('#thankYouInput').val(currentFlow.steps[currentFlow.steps.length - 1].prompt);
      const formSelectOptions = document.getElementById('formSelect').children;

      for (let i = 0; i < formSelectOptions.length; i++) {
        if (formSelectOptions[i].value === currentFlow.foreignPath) {
          formSelectOptions[i].selected = 'selected';
        }
      }
      //Prepopulate steps
      const stepsWithoutThankCount = currentFlow.steps.length - 1;
      let pElementsArray = [];
      for (let i = 0; i < stepsWithoutThankCount; i++) {
        const pElement = document.createElement('p');
        const selectElement = document.createElement('select');
        if (i === 0) {
          selectElement.id = 'fieldSelect1';
          selectElement.name = 'field1';
        }
        const optionElement1 = document.createElement('option');
        optionElement1.value = 'firstName';
        optionElement1.text = 'First Name';
        if (currentFlow.steps[i].foreignName === optionElement1.value) {
          optionElement1.selected = 'selected';
        }
        const optionElement2 = document.createElement('option');
        optionElement2.value = 'lastName';
        optionElement2.text ='Last Name';
        if (currentFlow.steps[i].foreignName === optionElement2.value) {
          optionElement2.selected = 'selected';
        }
        const optionElement3 = document.createElement('option');
        optionElement3.value = 'email';
        optionElement3.text = 'E-mail';
        if (currentFlow.steps[i].foreignName === optionElement3.value) {
          optionElement3.selected = 'selected';
        }
        const optionElement4 = document.createElement('option');
        optionElement4.value = 'zipCode';
        optionElement4.text = 'Zip Code';
        if (currentFlow.steps[i].foreignName === optionElement4.value) {
          optionElement4.selected = 'selected';
        }
        const inputElement = document.createElement('input');
        inputElement.value = currentFlow.steps[i].prompt;
        inputElement.oninput = function(event) {
          currentFlow.steps[i].prompt = event.target.value;
        }
        selectElement.append(optionElement1);
        selectElement.append(optionElement2);
        selectElement.append(optionElement3);
        selectElement.append(optionElement4);
        selectElement.onchange = function(event) {
          currentFlow.steps[i].foreignName = event.target.value;
        }

        pElement.append('Request for field');
        pElement.append(selectElement);
        pElement.append(inputElement);
        pElementsArray.push(pElement);

      }
      $('#requestDiv').empty();
      $('#requestDiv').append(pElementsArray);
    }
  }

  function attachSubmitAndCloseForm() {
    $("#form").submit(function(event) {
      event.preventDefault();
      const formObject = event.target.elements;

      if (currentFlow.id) {
        putFlow(currentFlow);
      } else {
        postFlows(currentFlow);
        $('#form')[0].reset();
      }
    });
    $("#closeForm").click(function() {
      $("#flow_form").toggle();
    });
  }

  function attachFormChangeEventHandlers() {
    $('#keyWordInput').on('input', function (event) {
      if (currentFlow) {
        currentFlow.activationKeyword = event.target.value;
      }
    });
    $('#thankYouInput').on('input', function (event) {
      if (currentFlow) {
        currentFlow.steps[currentFlow.steps.length - 1].prompt = this.value;
      }
    });
    $('#titleInput').on('input', function (event) {
      if (currentFlow) {
        currentFlow.title = event.target.value;
      }
    });

    $('#formSelect').on('change', function (event) {
      if (currentFlow) {
        currentFlow.foreignPath = this.value;
      }
    });

    $('#fieldSelect1').on('change', function(event) {
      if (currentFlow) {
        currentFlow.steps[0].foreignName = this.value;
      }
    });
    $('#fieldInput1').on('input', function(event) {
      if (currentFlow) {
        currentFlow.steps[0].prompt = this.value;
      }
    });
  }

  function fetchForms() {
    var xhttp = new XMLHttpRequest();
    xhttp.addEventListener("load", handleForms);
    xhttp.open("GET", ajaxurl + "?action=fetch_forms", true);
    xhttp.send();
  }

  function handleForms() {
    const data = JSON.parse(this.responseText);
    const formsIds = data['_embedded']['osdi:forms'];
    let optionsList = [];
    for (let i = 0; i < formsIds.length; i++) {
      const currentFormObject = formsIds[i];
      let option = document.createElement('option');
      option.value = currentFormObject.browser_url;
      option.text = currentFormObject.title;
      optionsList.push(option);
    }
    $('#formSelect').html();
    $('#formSelect').append(optionsList);

  }

  function getTaggingsCount(elem) {
      theTag = elem.value;
      var xhttp = new XMLHttpRequest();
      xhttp.addEventListener("load", handleTaggingsResponse);
      xhttp.open("GET", ANAdress + "tags/" +elem.value+ "/taggings/", true);
      xhttp.setRequestHeader("OSDI-API-Token", ANapiKey);
      xhttp.send();
  }

  function handleTaggingsResponse() {
      var total = document.getElementById("recipientTotal");
      var data = JSON.parse(this.responseText);
       total.innerHTML = "Total recipients: " + data["total_records"];
      document.getElementById("sendButton").disabled = false;
  }

  function addToSelect(content, value) {
      var newTag = document.getElementById("tags");
      var option = document.createElement("option");
      option.text = content;
      option.value = value;
      newTag.add(option);
  }

  //////////////////////////////////////////////////////////////////////////////////////////////
  // Send text message
  function tester() {
      var message = document.getElementById("message").value;
      var phone = document.getElementById("phone").value;
      var xhttp = new XMLHttpRequest();
      xhttp.addEventListener("load", serverResponse);
      xhttp.open("POST", sendServer, true);
      xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhttp.send("action=send_test_text&body="+message+"&to="+phone);
  }

  function postFlows(inputObject) {
    const xhttp = new XMLHttpRequest();
    xhttp.addEventListener("load", postFlowResponse);
    xhttp.open("POST", sendServer + "?action=post_flow", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("body=" + JSON.stringify(inputObject));
  }

  function postFlowResponse() {
    getFlows();
    $('#flow_form').toggle();
  }

  function putFlow(inputObject, fromRadioCheck = false, updateOldActive = false) {
    const xhttp = new XMLHttpRequest();
    if (!updateOldActive) {
      xhttp.addEventListener("load", putFlowResponse.bind(xhttp, fromRadioCheck));
    }
    xhttp.open("POST", sendServer + "?action=put_flow", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("body=" + JSON.stringify(inputObject));
  }

  function putFlowResponse(fromRadioCheck) {
    getFlows();
    if (!fromRadioCheck) {
      $('#flow_form').toggle();
    }
  }

  // Send real messages
  function sender() {
      if (confirm(document.getElementById("recipientTotal").innerHTML)) {
          var message = document.getElementById("message").value;
          var xhttp = new XMLHttpRequest();
          xhttp.addEventListener("load", checkProgress);
          xhttp.open("POST", sendServer, true);
          xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          xhttp.send("action=send_bulk_text&body=" + message + "&tags=" + theTag);
          $("#sendingTexts").show();
      }
  }

  function checkProgress() {
      var response = JSON.parse(this.responseText);
      if (response.finish) {
          document.getElementById("response").innerHTML = "FINISHED!<br>Total sent: "
              +response.messagesSent+ "<br>Total missing numbers: "
              +response.missingNumbers+ "<br>Total errors: " +response.errors+ "<br>";
          $("#sendingTexts").hide();
      } else if (response.errMsg) {
          document.getElementById("response").innerHTML = response.errMsg;
      } else {
          document.getElementById("response").innerHTML = "Sent " + response.messagesSent + " messages";
      }

      setTimeout(function() {
          var xhttp = new XMLHttpRequest();
          xhttp.addEventListener("load", checkProgress);
          xhttp.open("GET", sendServer + "?action=check_progress&pid=" + response.id, true);
          xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          xhttp.send();
      }, 30000);
  }

  // Post response from server
  function serverResponse() {
      document.getElementById("response").innerHTML = this.responseText;
  }
  $("#sendingTexts").hide();
</script>
