// src/utils/useTypograf.js
export const typographText = (text) => {
  if (!text || typeof text !== 'string') return text;

  let result = text.replace(/\u00A0/g, ' ');

  // Кавычки (не стоит использовать тк ломает ссылки и атрибуты)
  // result = result.replace(/"([^"]+)"/g, '«$1»');

  // Тире между словами
  result = result.replace(/(\s)-(\s)/g, '$1—$2');

  // Короткое тире между числами
  result = result.replace(/(\d)\s*-\s*(\d)/g, '$1–$2');

  // Неразрывные пробелы после коротких слов
  const shortWords = ['в', 'и', 'к', 'с', 'у', 'о', 'а', 'я', 'он', 'но', 'за', 'из', 'от', 'до', 'по', 'не', 'на', 'из', 'без'];
  shortWords.forEach(word => {
    const regex = new RegExp(`(^|\\s)${word}(\\s|$|[.,!?])`, 'gi');
    result = result.replace(regex, `$1${word}\u00A0`);
  });

  // Неразрывные пробелы перед единицами измерения
  result = result.replace(/(\d+)\s*(руб|р|USD|\$|EUR|€|кг|г|см|м|км|ч|мин|сек)/gi, '$1\u00A0$2');

  return result;
};


// Универсальный хук для подключения к любому блоку
export const useTypograf = (attributes, setAttributes, fields) => {
  const typographField = (fieldName) => {
    const value = attributes[fieldName];
    if (value) {
      setAttributes({ [fieldName]: typographText(value) });
    }
  };

  const typographAllFields = () => {
    const newAttributes = {};
    fields.forEach((field) => {
      if (attributes[field]) {
        newAttributes[field] = typographText(attributes[field]);
      }
    });
    setAttributes(newAttributes);
  };

  return { typographField, typographAllFields };
};
