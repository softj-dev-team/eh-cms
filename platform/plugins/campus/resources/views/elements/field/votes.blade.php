
@if( $options['readonly'] == 'true')
<style>
    .form-actions-default{
        display: none !important;
    }

    .form-actions-fixed-top .btn-set {
        display: none !important;
    }
</style>

@endif

<div class="form-group">
        <label for="vote" class="control-label required" aria-required="true">Vote</label>
        <div id="rateYo" ></div>
        <input type="hidden" name="votes" id="starVote" value="{{FLOOR($options['value'] ?? 5) }}">

</div>
<script>
        $(function(){
            $("#rateYo").rateYo({

                rating: {{FLOOR($options['value'] ?? 5)}},
                readOnly: {{ $options['readonly'] }},
                fullStar: true,
                ratedFill: "#EC1469",
                starWidth : '24px',
            })
            .on("rateyo.set", function (e, data) {
                $('#starVote').val( data.rating);
            });

        })
</script>
