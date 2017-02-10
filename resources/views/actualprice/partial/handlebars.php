<script id="marker-template" type="text/x-handlebars-template">
<div class="marker">
    <div class="marker-content" onclick="markerClick(this)" data-id="{{ id }}" data-main-no="{{ main_no }}" data-sub-no="{{ sub_no }}">
        <span class="name">{{ name }}</span>
        <div class="price">{{ price }}만</div>
        <div class="size">{{ size }}m&sup2;</div>
        <div class="year">{{ year }}년</div>
    </div>
</div>
</script>

<script id="actual-price" type="text/x-handlebars-template">
<tr>
    <td>{{ size }}m&sup2;</td>
    <td>{{ year }}년 {{ month }}월</td>
    <td>{{ day }}일</td>
    <td>{{ price }}만원</td>
</tr>
</script>