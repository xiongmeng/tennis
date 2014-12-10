<div class="row ">
    <div class="col-md-10 bg-light" data-bind="visible: last_page()>1" style="display: none">
        <ul class="pagination">
            <!--ko switch: current_page()<=1-->
            <li class="disabled" data-bind="case.visible: true"><span>«</span></li>
            <li data-bind="case.visible: false"><a href="javascript:void(0)" data-bind="click: pre">«</a>
            </li>
            <!--/ko-->

            <!--ko foreach: pages-->
            <!--ko switch: name==$root.current_page() || name=="..."-->
            <li data-bind="case.visible: true, css:{active:name==$root.current_page(), disabled:name=='...'}">
                <span data-bind="text:name"></span>
            </li>
            <li data-bind="case.visible: false">
                <a href="javascript:void(0)" data-bind="click:$root.go, text:name"></a>
            </li>
            <!--/ko-->
            <!--/ko-->

            <!--ko switch: current_page()==last_page()-->
            <li class="disabled" data-bind="case.visible: true"><span>»</span></li>
            <li data-bind="case.visible: false"><a href="javascript:void(0)" data-bind="click: next">»</a>
            </li>
            <!--/ko-->
        </ul>
    </div>
</div>