	var dragsort = ToolMan.dragsort()
	var junkdrawer = ToolMan.junkdrawer()

	window.onload = function() {
		junkdrawer.restoreListOrder("boxes_top_post");
		dragsort.makeListSortable(document.getElementById("boxes_top_post"),saveOrder);
		junkdrawer.restoreListOrder("boxes_top");
		dragsort.makeListSortable(document.getElementById("boxes_top"),saveOrder);
        junkdrawer.restoreListOrder("boxes_bottom_post");
		dragsort.makeListSortable(document.getElementById("boxes_bottom_post"),saveOrder);
        junkdrawer.restoreListOrder("boxes_bottom");
		dragsort.makeListSortable(document.getElementById("boxes_bottom"),saveOrder);
        junkdrawer.restoreListOrder("boxes_float");
		dragsort.makeListSortable(document.getElementById("boxes_float"),saveOrder);
	}

	function verticalOnly(item) {
		item.toolManDragGroup.verticalOnly()
	}

	function speak(id, what) {
		var element = document.getElementById(id);
		element.innerHTML = 'Clicked ' + what;
	}

	function saveOrder(item) {
	/*	var group = item.toolManDragGroup
		var list = group.element.parentNode
		var id = list.getAttribute("id")
		if (id == null) return
		group.register('dragend', function() {
			ToolMan.cookies().set("list-" + id,
					junkdrawer.serializeList(list), 365)
		})
	*/}

