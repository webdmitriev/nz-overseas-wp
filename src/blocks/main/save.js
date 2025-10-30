import { useBlockProps, RichText } from '@wordpress/block-editor';

const Save = ({ attributes }) => {
  const {
    title,
    subTitle,
    imageUrl,
    items
  } = attributes;

  const blockProps = useBlockProps.save({
    className: `main`,
  });

  // <RichText.Content tagName="span" value={supTitle} className="sup-title" />
  return (
    <div {...blockProps}>
      <div className="container">
        <div className="line-wrap">
          <div className="main-content">
            <RichText.Content tagName="h1" value={title} className="h1" />
            <RichText.Content tagName="p" value={subTitle} className="sub_title" />

            <div className="candle candle-01"></div>
            <div className="candle candle-02"></div>
            <div className="candle candle-03"></div>
            <div className="candle candle-04"></div>
            <div className="candle candle-05"></div>
            <div className="candle candle-06"></div>
          </div>
          <div className="main-images">
            {imageUrl && (
              <div className="advanced-block-image">
                <img
                  src={imageUrl}
                  alt="NZ Overseas"
                  className="image-01"
                  loading="lazy"
                  decoding="async"
                />
              </div>
            )}
            <div className="images-container">
              <div className="rhombus-top"></div>
              <div className="rhombus-bottom"></div>
              <div className="square-bg-top"></div>
              <div className="square-bg-bottom"></div>
              <div className="square square-01"></div>
              <div className="square square-02"></div>
              <div className="square square-03"></div>
              <div className="square square-04"></div>
              <div className="square square-05"></div>
              <div className="square square-06"></div>
              <svg className="svg-line" width="604" height="559" viewBox="0 0 604 559" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path id="graph-line" d="M-326.5 571.5L-271.5 524L-215 508.5L-163 559.5L-73.5 490.5L-53 497L-7 494L61 413.5L151 449.5L235 364L261 366L332.5 273L361.5 314L396 294L426.5 239L496.5 194L546.5 62L665.5 14.5L730 64.5L753 117.5L777.5 127L816.5 195L840.5 169.5L869.5 180.5L903.5 185.5L925 202.5L961.5 160.5L1020 49L1063.5 117.5L1095 134.5L1136.5 78.5L1158 102L1184 90.5L1248 3.5L1279.5 49L1313.5 28L1375 -20L1400 1L1450.5 -12.5L1486 -31.5L1507 -22.5H1545L1585.5 -72L1593 -78" stroke="#0F5EAF" stroke-width="9" />
              </svg>
              <div className="cup cup-01"></div>
              <div className="cup cup-02"></div>
              <div className="cup cup-03"></div>
              <div className="cup cup-04"></div>
              <div className="cup cup-05"></div>
              <div className="cup cup-06"></div>
              <div className="cup cup-07"></div>
              <div className="cup cup-08"></div>
              <div className="cup cup-09"></div>
              <div className="cup cup-10"></div>
              <div className="cup cup-11"></div>
              <div className="cup cup-12"></div>
              <div className="cup cup-13"></div>
              <div className="cup cup-14"></div>
              <div className="cup cup-15"></div>
              <div className="cup cup-16"></div>
              <div className="cup cup-17"></div>
              <div className="cup cup-18"></div>
              <div className="cup cup-19"></div>
              <div className="cup cup-20"></div>
              <div className="cup cup-21"></div>
              <div className="cup cup-22"></div>
              <div className="cup cup-23"></div>
              <div className="cup cup-24"></div>
              <div className="cup cup-25"></div>
              <div className="ogive ogive-01"></div>
              <div className="ogive ogive-02"></div>
              <div className="ogive ogive-03"></div>
              <div className="ogive ogive-04"></div>
            </div>
          </div>
        </div>
        <div className="line-items">
          {items.map((item, index) => {
            if (!item) return null;
            return (
              <div key={index} className={`line-item line-item-0${index + 1}`}>
                {item.content && (
                  <RichText.Content tagName="p" value={item.content} />
                )}
              </div>
            );
          })}
        </div>
      </div>
    </div>
  );
};

export default Save;
