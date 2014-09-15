var defaultInputValue = new Hash();
var quickwriteCounter = 0;

document.observe("dom:loaded", function()
{
	enableQuickwrite();
});

function enableQuickwrite()
{
	var inputs = $$("input[class*='quickwrite']");
	var textareas = $$("textarea");
	inputs = inputs.concat(textareas);
	inputs.each
	(
		function(e)
		{
			if (defaultInputValue.get(e.id) == null)
			{
				if (e.id == "")
					e.id = "quickwrite" + quickwriteCounter++;
				defaultInputValue.set(e.id, e.value);
				e.observe("focus", function()
				{
					if (e.value == defaultInputValue.get(e.id))
					{
						e.value = "";
					}
				});
				e.observe("blur", function()
				{
					if (e.value == "")
					{
						e.value = defaultInputValue.get(e.id);
					}
				});
			}
		}
	);
}