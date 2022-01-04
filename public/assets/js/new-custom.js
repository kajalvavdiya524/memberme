$(document).ready(function () {

    // ================= Login Redirection to Dashboard =========================

    $("#loginform").find("button").click(function () {
        window.location.href = "/index5.html";
    });

    // ======================================= Data Table Initialization ===============================

    $('.my-dataTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });

    $('.my-dashboard-table').DataTable({
        "iDisplayLength": 5,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });

    // ======================================= Slim Scroll Initialization ===============================

    $('.my-detail-modal .modal-body').slimScroll({
        height: '650',
        color: '#dcdcdc',
        alwaysVisible: true

    });

    $('.multi-dd-list').slimScroll({
        height: '300',
        color: '#dcdcdc',
        alwaysVisible: true
    });

    // ======================================= X-Editable Initialization ===============================

    $('.inline-text').editable({
        mode: 'inline'
    });

    $('.inline-firstname').editable({
        validate: function (value) {
            if ($.trim(value) === '') return 'This field is required';
        },
        mode: 'inline'
    });

    $('.inline-lastname').editable({
        validate: function (value) {
            if ($.trim(value) === '') return 'This field is required';
        },
        mode: 'inline'
    });

    $('#title').editable({
        mode: 'inline',
        value: 1,
        source: [
            {value: 1, text: 'Mr'},
            {value: 2, text: 'Mrs'}
        ]
    });

    $('#gender').editable({
        mode: 'inline',
        value: '1',
        source: [
            {value: 1, text: 'Male'},
            {value: 2, text: 'Female'}
        ]
    });

    $('#industry').editable({
        mode: 'inline',
        prepend: 'Select Industry',
        source: [
            {value: 1, text: 'Restaurant'},
            {value: 2, text: 'Cafe'},
            {value: 3, text: 'Bar'},
            {value: 4, text: 'Hotel'}
        ]
    });

    $('#password').editable({
        type: 'password',
        name: 'password',
        mode: 'inline'
    });

    $('.event-date-time').editable({
        mode: 'inline',
        placement: 'right',
        combodate: {
            firstItem: 'name'
        }
    });

    $('#attendees').editable({
        mode: 'inline',
        value: '1',
        source: [
            {value: 1, text: 'Option1'},
            {value: 2, text: 'Option2'}
        ]
    });

    $('.yesno').editable({
        mode: 'inline',
        value: '1',
        source: [
            {value: 1, text: 'Yes'},
            {value: 2, text: 'No'}
        ]
    });

    $('#rsaType').editable({
        value: '1',
        mode: 'inline',
        source: [
            {value: 1, text: 'Returned'},
            {value: 2, text: 'Sent'}
        ]
    });

    $('#change-to').editable({
        mode: 'inline',
        value: '1',
        source: [
            {value: 1, text: 'Full Member'},
            {value: 2, text: 'Option 2'}
        ]
    });

    $('#date-effective').editable({
        mode: 'inline',
        value: '1',
        source: [
            {value: 1, text: 'Immediate'},
            {value: 2, text: 'Option 2'}
        ]
    });

    $('#upgrade-plan').editable({
        mode: 'inline',
        emptytext: 'Select Plan',
        prepend: 'Select Plan',
        source: [
            {value: 1, text: 'Bronze $20'},
            {value: 2, text: 'Silver $40'},
            {value: 3, text: 'Gold $60'},
            {value: 4, text: 'Platinum $80'}
        ]
    });

    $('#current-status').editable({
        mode: 'inline',
        emptytext: 'Select Status',
        value: '1',
        source: [
            {value: 1, text: 'Standard'},
            {value: 2, text: 'Full Member'},
            {value: 3, text: 'Suspended'},
            {value: 4, text: 'On Hold'},
            {value: 5, text: 'Non Financial'},
            {value: 6, text: 'Expired'}
        ]
    });

    $('#reason').editable({
        mode: 'inline',
        emptytext: 'Enter your reason here',
        showbuttons: 'bottom'
    });

    $('#note-field').editable({
        mode: 'inline',
        emptytext: 'Enter your note here ...'
    });

    $('#select-gateway').editable({
        mode: 'inline',
        emptytext: 'Select Gateway',
        value: '1',
        source: [
            {value: 1, text: 'Paystation'},
            {value: 2, text: 'Paypal'},
            {value: 3, text: 'Braintree'}
        ]
    });

    $('#profile-note').editable({
        showbuttons: 'bottom',
        mode: 'inline'
    });

    // ==================================== Overview Page ======================================

    // --------------------------------- Search Fields ------------------------------

    //Add Search Tags
    $("#add-new-field").on('click', function () {

        $(".selectpicker").selectpicker("destroy");

        $("#add-field-clone").find("> div").clone().appendTo("#member-search-fields").hide().fadeIn();
        $("#member-search-fields").find("select").selectpicker();
        removeFieldOnEach();
    });

    //removing search field for newly added html
    removeFieldOnEach();
    function removeFieldOnEach() {
        $(".btn-remove-field").on('click', function () {
            $(this).closest(".row").fadeOut().remove();
        });
    }

    // --------------------------------- New Member Modal ------------------------------

    // Member Modal Validations
    $("#add-member-form").find(".required").not("[type=submit]").jqBootstrapValidation({

        submitError: function ($form, event, errors) {

        },
        submitSuccess: function ($form, event) {

            $("#add-new-member").modal('hide');
            $("#user-detail-modal").attr("data-call", "add-new-member").modal('show');
            $("body").addClass("my-modal-open");
            event.preventDefault();

        }
    });
    //Add New Member Modal
    $("#submit-new-member").on('click', function () {
        $("#add-member-form").submit();
    });

    // --------------------------------- User Detail Modal ------------------------------

    //Go back if it is opened from Expiring Members
    $("#user-detail-modal").on('hidden.bs.modal', function (e) {
        var call = $(this).attr("data-call");

        if (call !== undefined) {
            $("body").removeClass("my-modal-open");
            $("body").css("padding-right", "0px");  // Bug from bootstrap modals
            $("#" + call).modal('show');
            $(this).removeAttr("data-call");

        }

    });

    // ----------------------------------- Expiring Modal  ------------------------------

    //Member Detail Modal in Modal having DataTable
    $(".modal .my-dataTable tbody tr").on('click', function () {
        $("body").addClass("my-modal-open");
        var id = $(this).closest(".modal").attr("id");
        $(this).closest(".modal").modal('hide');
        $("#user-detail-modal").attr("data-call", id).modal('show');
    });

    // ----------------------------------- Members Display Modal  ------------------------------

    //Member Detail Modal Trigger    ----------- Not in Expiring Members
    $(".my-dashboard-table tbody tr,.my-dataTable:not(.in-modal) tbody tr").on('click', function () {
        $("#user-detail-modal").modal('show');
    });

    // ----------------------------------------- Modals inside Modals ------------------------

    $("#add-subscription-btn").on('click', function () {
        $("body").addClass("my-modal-open");
        $("#organization-detail-modal").modal('hide');
        $("#add-subscription-modal").attr("data-call", "organization-detail-modal").modal('show');
    });

    $(".show-invoice").on('click', function () {
        $("body").addClass("my-modal-open");
        $("#user-detail-modal").modal('hide');
        $("#invoice-modal").attr("data-call", "user-detail-modal").modal('show');
    });

    $("#update-subscription-btn").on('click', function () {
        // No Condition because it can only be called from user detail modal
        var callFrom = $("#user-detail-modal").attr("data-call");
        $("#user-detail-modal").removeAttr("data-call");
        $("#update-subscription-modal").attr("data-call-second", callFrom);

        $("body").addClass("my-modal-open");
        $("#user-detail-modal").modal('hide');
        $("#update-subscription-modal").attr("data-call", "user-detail-modal").modal('show');

    });

    $("#add-virtual-cards").on('click', function () {
        $("body").addClass("my-modal-open");
        $("#virtual-cards-modal").modal('hide');
        $("#add-virtual-cards-modal").attr("data-call", "virtual-cards-modal").modal('show');
    });

    $("#gateway-toggle:checked").on('change', function () {

        if ($("#gateway-toggle").is(":checked")) {
            $("body").addClass("my-modal-open");
            $("#organization-detail-modal").modal('hide');
            $("#gateway-detail-modal").attr("data-call", "organization-detail-modal").modal('show');
        }

    });

    $("#adjuncts-picker").on('changed.bs.select', function (e) {
        $("#adjuncts-list").empty();

        var selectedAdjuncts = $("#adjuncts-picker").val();
        for (var i = 0; i < selectedAdjuncts.length; i++) {
            $("#adjuncts-list").append('<li class="list-group-item">' + selectedAdjuncts[i] + '</li>');
        }
    });

    $("#activities-picker").on('changed.bs.select', function (e) {
        $("#activities-list").empty();

        var selectedAdjuncts = $("#activities-picker").val();
        for (var i = 0; i < selectedAdjuncts.length; i++) {
            $("#activities-list").append('<li class="list-group-item">' + selectedAdjuncts[i] + '</li>');
        }
    });

    $("#add-interest").on('click', function (e) {

        var value = $("#interest").val();
        $("#interest").val("");

        if (value !== "") {
            $("#interest-list").append('<li class="list-group-item">' + value + '</li>');
        }
        else {
            alert("Field is empty");
        }
    });

    $("#org-adjuncts-add-btn").on('click', function (e) {

        var value = $("#org-adjunct").val();
        $("#org-adjunct").val("");

        if (value !== "") {
            $("#org-adjuncts-list").append('<li class="list-group-item">' + value + '</li>');
        }
        else {
            alert("Field is empty");
        }
    });

    $("#org-activities-add-btn").on('click', function (e) {

        var value = $("#org-activity").val();
        $("#org-activity").val("");

        if (value !== "") {
            $("#org-activities-list").append('<li class="list-group-item">' + value + '</li>');
        }
        else {
            alert("Field is empty");
        }
    });

    $("#add-industry-btn").on('click', function (e) {

        var value = $("#add-industry").val();
        $("#add-industry").val("");

        if (value !== "") {
            $("#industry-list").append('<li class="list-group-item">' + value + '</li>');
        }
        else {
            alert("Field is empty");
        }
    });

    $("#add-reward").on('click', function () {

        //Getting Form Data
        var name = $("#reward-name").val();
        var stamps = $("#stamps").val();
        var message = $("#reward-message").val();

        //Getting New Sr
        var sr = $("#rewards-table").find(".last").data("sr");

        if (sr === undefined) {
            sr = 0;
        }

        var newSr = ++sr;
        $("#rewards-table").find(".last").removeClass("last");

        //Making New Row
        var newRow = '<tr style="display: none;" class="last" data-sr="' + newSr + '">';
        newRow += '<td class="text-center">Reward ' + newSr + '</td>';
        newRow += '<td> ' + name + ' </td>';
        newRow += '<td> ' + stamps + ' </td>';
        newRow += '<td> ' + message + ' </td></tr>';

        //Putting New Row on Table
        $("#reward-entry").before(newRow);
        $("#rewards-table").find(".last").fadeIn();

        //Clear Form for New Entry
        $("#reward-name").val("");
        $("#stamps").val("");
        $("#reward-message").val("");

    });

    // Upload Virtual Card Image
    $("#upload-identity").on('click', function () {

        $("#identity-card-btn").click();
    });

    $("#identity-card-btn").on('change', function (e) {
        if (e.currentTarget.files.length > 0) {
            showPreloader(this);
            var element = this;
            setTimeout(function () {
                showUploadedImage(element, e, ".card-image img");
                showUploadedImage(element, e, "#identity .profile-img img");
                hidePreloader(element);
            }, 2000);
        }
    });

    //Upload Card Design
    $("#upload-card-design").on('click', function () {
        $("#card-design-btn").click();
    });

    $("#card-design-btn").on('change', function (e) {
        if (e.currentTarget.files.length > 0) {
            showPreloader(this);

            var element = this;
            setTimeout(function () {
                showUploadedImage(element, e, ".virtual-card-design img");
                hidePreloader(element);
            }, 2000);
        }
    });

    // Upload Card Bg
    $("#upload-card-bg-btn").on('click', function () {

        $("#upload-card-bg").click();
    });

    $("#upload-card-bg").on('change', function (e) {
        if (e.currentTarget.files.length > 0) {
        showPreloader(this);

        var element = this;
        setTimeout(function () {
            showUploadedImage(element, e, "#identity .virtual-card > img");
            hidePreloader(element);
        }, 2000);
        }

    });

    // Upload Logo
    $("#upload-logo-btn").on('click', function (e) {

        $("#upload-logo").click();
    });

    $("#upload-logo").on('change', function (e) {

        showPreloader(this);

        var element = this;
        setTimeout(function () {
            showUploadedImage(element, e, "#logo .organization-logo img");
            hidePreloader(element);
        }, 2000);
    });

    //Change Subscription Status
    $('#current-status').on('save', function (e, params) {
        if (params.newValue === "3" || params.newValue === "4") {
            $("body").addClass("my-modal-open");
            $("#user-detail-modal").modal('hide');
            $("#current-status-reason-modal").attr("data-call", "user-detail-modal").modal('show');
        }
    });

    // Modals

    $("#add-subscription-modal").on('hidden.bs.modal', function (e) {
        var call = $(this).attr("data-call");

        if (call !== undefined) {
            $("body").removeClass("my-modal-open");
            $("body").css("padding-right", "0px");  // Bug from bootstrap modals
            $("#" + call).modal('show');
            $(this).removeAttr("data-call");
        }
    });

    $("#gateway-detail-modal").on('hidden.bs.modal', function (e) {
        var call = $(this).attr("data-call");

        if (call !== undefined) {
            $("body").removeClass("my-modal-open");
            $("body").css("padding-right", "0px");  // Bug from bootstrap modals
            $("#" + call).modal('show');
            $(this).removeAttr("data-call");
        }
    });

    $("#add-virtual-cards-modal").on('hidden.bs.modal', function (e) {
        var call = $(this).attr("data-call");

        if (call !== undefined) {
            $("body").removeClass("my-modal-open");
            $("body").css("padding-right", "0px");  // Bug from bootstrap modals
            $("#" + call).modal('show');
            $(this).removeAttr("data-call");
        }
    });

    $("#update-subscription-modal").on('hidden.bs.modal', function (e) {
        var call = $(this).attr("data-call");
        var callSecond = $(this).attr("data-call-second");

        if (call !== undefined) {
            $("body").removeClass("my-modal-open");
            $("body").css("padding-right", "0px");  // Bug from bootstrap modals
            $("#" + call).modal('show');

            $(this).removeAttr("data-call-second");
            $("#user-detail-modal").attr("data-call", callSecond);

        }
    });

    $("#current-status-reason-modal").on('hidden.bs.modal', function (e) {
        var call = $(this).attr("data-call");

        if (call !== undefined) {
            $("body").removeClass("my-modal-open");
            $("body").css("padding-right", "0px");  // Bug from bootstrap modals
            $("#" + call).modal('show');

            $(this).removeAttr("data-call");
        }
    });

    $("#invoice-modal").on('hidden.bs.modal', function (e) {
        var call = $(this).attr("data-call");

        if (call !== undefined) {
            $("body").removeClass("my-modal-open");
            $("body").css("padding-right", "0px");  // Bug from bootstrap modals
            $("#" + call).modal('show');

            $(this).removeAttr("data-call");
        }
    });


    // ===================================== Comms Page ================================================

    // Javascript to enable link to tab
    var url = document.location.toString();
    if (url.match("#")) {
        $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
    }

    // Change hash for page-reload
    $('.nav-tabs a').on('shown.bs.tab', function (e) {
        var hash = e.target.hash;
        if (hash === "#sms-tab") {
            window.location.hash = hash;
            window.scrollTo(0, 0);
        }
    });


    //=========================================== Generic Functions ====================================

    //Show Preloader
    function showPreloader(element) {
        $(element).closest(".div-my-preloader").find(".my-preloader").fadeIn();
    }

    //Hide Preloader
    function hidePreloader(element) {
        $(element).closest(".div-my-preloader").find(".my-preloader").fadeOut();
    }

    //Show Image on Local Upload
    function showUploadedImage(element, e, imageSelector) {

        var fReader = new FileReader();
        fReader.readAsDataURL(element.files[0]);
        fReader.onloadend = function (e) {

            var src = e.target.result;
            $(imageSelector).attr("src", src);
        };
    }
});