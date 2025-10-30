import { useBlockProps } from '@wordpress/block-editor';

const Save = ({ attributes }) => {
  const { shortcode } = attributes;
  const blockProps = useBlockProps.save({ className: 'request' });

  if (!shortcode) return null;

  return <section {...blockProps}>
    <div className="container">
      <div className="line-wrap">
        <h2 className="h2">Leave a request and we will contact <br />you during business hours.</h2>
        {shortcode}
      </div>
    </div>
  </section>;
};

export default Save;
