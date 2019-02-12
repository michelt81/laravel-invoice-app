<?php

/**
 * Create a select box field where id, name, and value keys serve the main purpose,
 * and all other attributes are used as data- attributes
 * @param  string $name
 * @param  array  $list
 * @param  string $selected
 * @param  array  $options
 *
 * @return \Illuminate\Support\HtmlString
 */
Form::macro('dataSelect', function ($name, $list = [], $selected = null, $options = [], $valueColumn = 'value', $displayColumn = 'display') {
    // When building a select box the "value" attribute is really the selected one
    // so we will use that when checking the model or session for a value which
    // should provide a convenient method of re-populating the forms on post.
    $selected = $this->getValueAttribute($name, $selected);

    $options['id'] = $this->getIdAttribute($name, $options);

    if (! isset($options['name'])) {
        $options['name'] = $name;
    }

    // We will simply loop through the options and build an HTML value for each of
    // them until we have an array of HTML declarations. Then we will join them
    // all together into one single HTML element that can be put on the form.
    $html = [];

    if (isset($options['placeholder'])) {
        $html[] = $this->placeholderOption($options['placeholder'], $selected);
        unset($options['placeholder']);
    }

    foreach ($list as $list_el)
    {

        $option_attr = array(
            'value' => e($list_el[$valueColumn]),
            'selected' => $this->getSelectedValue($list_el[$valueColumn], $selected),
        );

        if (is_object($list_el)) {
            $keys = array_keys($list_el->toArray());
        } else {
            $keys = array_keys($list_el);
        }

        foreach ($keys as $key) {
            if (!in_array($key, [$valueColumn, $displayColumn, 'class'])) {
                $option_attr['data-' . $key] = e($list_el[$key]);
            }
        }
        $html[] = '<option'.$this->html->attributes($option_attr).'>'.e($list_el[$displayColumn]).'</option>';
    }

    // Once we have all of this HTML, we can join this into a single element after
    // formatting the attributes into an HTML "attributes" string, then we will
    // build out a final select statement, which will contain all the values.
    $options = $this->html->attributes($options);

    $list = implode('', $html);

    return $this->toHtmlString("<select{$options}>{$list}</select>");
});